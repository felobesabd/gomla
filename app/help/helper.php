<?php

use App\Enums\NotificationTypes;
use App\Models\Attachment;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Freelancer;
use App\Models\SeenNotification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

function checkUseHasPermission($permission)
{
    $user = auth()->user();

    if (!$user || !$user->can($permission)) {
        return false;
    }

    return true;
}

function checkUserHasRolesOrRedirect($permission)
{
    if (!checkUseHasPermission($permission)) {
        Redirect::to('/' . target() . '?error=you do not have any permission to access this page')->send();
        die();
    }
}

function target()
{
    /** @var \App\Models\User $user */
    $user = auth()->user();
    if (!$user) {
        return '/';
    }

    return 'admin';
}

function targetRole(?int $user_id = null, bool $get_freelancer_type = false): ?string
{
    if ($user_id) {
        /** @var \App\Models\User $user */
        $user = User::find($user_id);
    } else {
        /** @var \App\Models\User $user */
        $user = auth()->user();
    }

    if (!$user) {
        return null;
    }
    if ($user->hasRole(['admin'])) {
        $target = 'admin';
    } else if ($user->hasRole(['customer'])) {
        $target = 'customer';
    }
    else if ($user->hasRole(['customer support'])) {
        $target = 'customer-support';
    } else if (!empty($user->freelancer_id)) {
        if ($get_freelancer_type)
            $target = getFreelancerRole(user: $user);
        else
            $target = 'freelancer';
    } else {
        $target = null;
    }
    return $target;
}

function getUserRole(?int $user_id = null): string{
    if ($user_id) {
        /** @var \App\Models\User $user */
        $user = User::find($user_id);
    } else {
        /** @var \App\Models\User $user */
        $user = auth()->user();
    }
    return $user->roles->pluck('name')[0];
}

function getFreelancerRole(User $user): ?string
{
    return Role::findById($user->freelancer_id)?->name;
}

/** edit or create file */
function editOrCreateFile(
    string $folder,
    Object $obj,
    ?UploadedFile $attachment = null,
    string $relation = 'attach',
    $attach_col_name = 'attachment_id',
) {
    if ($attachment) {
        $newname =  $obj->id . '_' . date("d_m_Y") . rand(1, 1000) . time() . '_' . rand(1, 1000) . '.' . $attachment->getClientOriginalExtension();
        $attachment->move(public_path('uploads/' . $folder), $newname);
        if ($obj->$relation?->path) {
            /* delete old file */
            $file_path = public_path($obj->$relation->path);
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }
        /* edit relation database */
        if (!$obj->$relation){
            return;
        }


        $obj->$relation->fill([
            'name' => $attachment->getClientOriginalName(),
            'path' => $folder . '/' . $newname
        ])->save();


        /* update attach_col_name  */
        $obj->$attach_col_name = $obj->$relation->id;
        $obj->save();
    }
}

function editOrCreateFileReturnId(
    string $folder,
    Object $obj,
    ?UploadedFile $attachment = null,
    string $relation = 'attach',
    string $attach_col_name = 'attachment_id'
): ?int {
    if ($attachment) {
        $newname = $obj->id . '_' . date("d_m_Y") . rand(1, 1000) . time() . '_' . rand(1, 1000) . '.' . $attachment->getClientOriginalExtension();

        $attachment->move(public_path('uploads/' . $folder), $newname);

        if ($obj->$relation && $obj->$relation->path) {
            $file_path = public_path($obj->$relation->path);
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        // Update the attachment model
        if ($obj->$relation) {
            $obj->$relation->fill([
                'name' => $attachment->getClientOriginalName(),
                'path' => $folder . '/' . $newname,
            ])->save();
        } else {
            $obj->$relation()->create([
                'name' => $attachment->getClientOriginalName(),
                'path' => $folder . '/' . $newname,
            ]);
        }

        $obj->$attach_col_name = $obj->$relation->id;
        $obj->save();

        return $obj->$relation->id;
    }
    return null;
}

/* delete file */
function deleteFile(object $obj, string $relation = 'attach')
{
    if ($obj->$relation?->path) {
        $file_path = public_path($obj->$relation->path);
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $obj->$relation->delete();
    }
}

/** edit or create multiple files */
function editOrCreateMultipleFiles(
    string $folder,
    Object $obj,
    array $attachments = [],
    string $attach_col_name = 'attachments_ids',
) {
    if (!empty($attachments)) {
        $new_attachments_ids = [];
        foreach ($attachments as $attachment) {
            $newname =  $obj->id . '_' . date("d_m_Y") . rand(1, 1000) . time() . '_' . rand(1, 1000) . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('uploads/' . $folder), $newname);
            if (!empty($obj->{$attach_col_name})) {
                /* delete old files */
                deleteMultipleFiles(obj: $obj, attach_col_name: $attach_col_name);
            }
            /* save in Attachment database */
            $new_attachment = Attachment::create([
                'name' => $attachment->getClientOriginalName(),
                'path' => $folder . '/' . $newname
            ]);
            $new_attachments_ids[] = $new_attachment->id;
        }
        /* update attach_col_name  */
        $obj->{$attach_col_name} = json_encode($new_attachments_ids);
        $obj->save();
    }
}

/* delete multiple files */
function deleteMultipleFiles(object $obj, string $attach_col_name = 'attachments_ids')
{
    $attachments = getMultipleAttachments(
        obj: $obj,
        attach_col_name: $attach_col_name,
    );
    foreach ($attachments as $attachment) {
        $file_path = public_path($attachment->path);
        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $attachment->delete();
    }
    $obj->$attach_col_name = [];
    $obj->save();
}

/** get multiple attachments */
function getMultipleAttachments(
    Object $obj,
    string $attach_col_name = '',
): Collection {
    $attach_ids = json_decode($obj->{$attach_col_name});
    if ($attach_ids === null) {
        return new Collection();
    } else {
        return Attachment::whereIn('id', $attach_ids)->get();
    }
}

/**
 * we use this function when edit or create object
 * call before editing but after creating object
 * @param string $model
 * @param Integer $min_ordering
 * @param object $obj
 * @return void
 */
function ordering(string $model, object $obj, ?int $new_ordering = null): void
{
    /** if editing */
    if ($new_ordering) {
        /** check that ordering changed for that object */
        if ($new_ordering != $obj->ordering) {
            /** check if no object exist equals this ordering */
            $exist_ordering = $model::query()
                ->where('ordering', $new_ordering)->first();
            if (!is_object($exist_ordering)) {
                return;
            }
            /** end check if no object exist equals this ordering */

            $model::query()
                ->where('ordering', '>=', $new_ordering)
                ->update([
                    'ordering' => DB::raw("ordering + 1")
                ]);
        }
    }
    /** if creating */
    else {
        $model::query()
            ->where('id', '!=', $obj->id)
            ->update([
                'ordering' => DB::raw("ordering + 1")
            ]);
    }
}


function formatName(string $word): string
{
    $res = str_replace('_', ' ', $word);
    return ucwords(strtolower($res));
}

function countries($sort = 'ksort')
{
    $array =

        [
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Democratic Republic of the Congo",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        ];
    /** sort countries by val in asc order */
    if ($sort == 'asort') {
        asort($array);
    } else {
        ksort($array);
    }
    $array2[''] = 'Choose';
    /** make no_country_assigned option at the first */
    $array = $array2 + $array;
    return $array;
}

function generate_country_codes()
{
    return "<!-- country codes (ISO 3166) and Dial codes. -->
        <select class='form-control countryCode'  name='country_code' id='countryCodeSelect'>
                    <option data-countryCode='' value='' selected disabled>Choose</option>
                    <option data-countryCode='EG' value='20'>Egypt (+20)</option>
                    <option data-countryCode='DZ' value='213'>Algeria (+213)</option>
                    <option data-countryCode='AD' value='376'>Andorra (+376)</option>
                    <option data-countryCode='AO' value='244'>Angola (+244)</option>
                    <option data-countryCode='AI' value='1264'>Anguilla (+1264)</option>
                    <option data-countryCode='AG' value='1268'>Antigua &amp; Barbuda (+1268)</option>
                    <option data-countryCode='AR' value='54'>Argentina (+54)</option>
                    <option data-countryCode='AM' value='374'>Armenia (+374)</option>
                    <option data-countryCode='AW' value='297'>Aruba (+297)</option>
                    <option data-countryCode='AU' value='61'>Australia (+61)</option>
                    <option data-countryCode='AT' value='43'>Austria (+43)</option>
                    <option data-countryCode='AZ' value='994'>Azerbaijan (+994)</option>
                    <option data-countryCode='BS' value='1242'>Bahamas (+1242)</option>
                    <option data-countryCode='BH' value='973'>Bahrain (+973)</option>
                    <option data-countryCode='BD' value='880'>Bangladesh (+880)</option>
                    <option data-countryCode='BB' value='1246'>Barbados (+1246)</option>
                    <option data-countryCode='BY' value='375'>Belarus (+375)</option>
                    <option data-countryCode='BE' value='32'>Belgium (+32)</option>
                    <option data-countryCode='BZ' value='501'>Belize (+501)</option>
                    <option data-countryCode='BJ' value='229'>Benin (+229)</option>
                    <option data-countryCode='BM' value='1441'>Bermuda (+1441)</option>
                    <option data-countryCode='BT' value='975'>Bhutan (+975)</option>
                    <option data-countryCode='BO' value='591'>Bolivia (+591)</option>
                    <option data-countryCode='BA' value='387'>Bosnia Herzegovina (+387)</option>
                    <option data-countryCode='BW' value='267'>Botswana (+267)</option>
                    <option data-countryCode='BR' value='55'>Brazil (+55)</option>
                    <option data-countryCode='BN' value='673'>Brunei (+673)</option>
                    <option data-countryCode='BG' value='359'>Bulgaria (+359)</option>
                    <option data-countryCode='BF' value='226'>Burkina Faso (+226)</option>
                    <option data-countryCode='BI' value='257'>Burundi (+257)</option>
                    <option data-countryCode='KH' value='855'>Cambodia (+855)</option>
                    <option data-countryCode='CM' value='237'>Cameroon (+237)</option>
                    <option data-countryCode='CA' value='1'>Canada (+1)</option>
                    <option data-countryCode='CV' value='238'>Cape Verde Islands (+238)</option>
                    <option data-countryCode='KY' value='1345'>Cayman Islands (+1345)</option>
                    <option data-countryCode='CF' value='236'>Central African Republic (+236)</option>
                    <option data-countryCode='CL' value='56'>Chile (+56)</option>
                    <option data-countryCode='CN' value='86'>China (+86)</option>
                    <option data-countryCode='CO' value='57'>Colombia (+57)</option>
                    <option data-countryCode='KM' value='269'>Comoros (+269)</option>
                    <option data-countryCode='CG' value='242'>Congo (+242)</option>
                    <option data-countryCode='CK' value='682'>Cook Islands (+682)</option>
                    <option data-countryCode='CR' value='506'>Costa Rica (+506)</option>
                    <option data-countryCode='HR' value='385'>Croatia (+385)</option>
                    <option data-countryCode='CU' value='53'>Cuba (+53)</option>
                    <option data-countryCode='CY' value='90392'>Cyprus North (+90392)</option>
                    <option data-countryCode='CY' value='357'>Cyprus South (+357)</option>
                    <option data-countryCode='CZ' value='42'>Czech Republic (+42)</option>
                    <option data-countryCode='DK' value='45'>Denmark (+45)</option>
                    <option data-countryCode='DJ' value='253'>Djibouti (+253)</option>
                    <option data-countryCode='DM' value='1809'>Dominica (+1809)</option>
                    <option data-countryCode='DO' value='1809'>Dominican Republic (+1809)</option>
                    <option data-countryCode='EC' value='593'>Ecuador (+593)</option>
                    <option data-countryCode='EG' value='20'>Egypt (+20)</option>
                    <option data-countryCode='SV' value='503'>El Salvador (+503)</option>
                    <option data-countryCode='GQ' value='240'>Equatorial Guinea (+240)</option>
                    <option data-countryCode='ER' value='291'>Eritrea (+291)</option>
                    <option data-countryCode='EE' value='372'>Estonia (+372)</option>
                    <option data-countryCode='ET' value='251'>Ethiopia (+251)</option>
                    <option data-countryCode='FK' value='500'>Falkland Islands (+500)</option>
                    <option data-countryCode='FO' value='298'>Faroe Islands (+298)</option>
                    <option data-countryCode='FJ' value='679'>Fiji (+679)</option>
                    <option data-countryCode='FI' value='358'>Finland (+358)</option>
                    <option data-countryCode='FR' value='33'>France (+33)</option>
                    <option data-countryCode='GF' value='594'>French Guiana (+594)</option>
                    <option data-countryCode='PF' value='689'>French Polynesia (+689)</option>
                    <option data-countryCode='GA' value='241'>Gabon (+241)</option>
                    <option data-countryCode='GM' value='220'>Gambia (+220)</option>
                    <option data-countryCode='GE' value='7880'>Georgia (+7880)</option>
                    <option data-countryCode='DE' value='49'>Germany (+49)</option>
                    <option data-countryCode='GH' value='233'>Ghana (+233)</option>
                    <option data-countryCode='GI' value='350'>Gibraltar (+350)</option>
                    <option data-countryCode='GR' value='30'>Greece (+30)</option>
                    <option data-countryCode='GL' value='299'>Greenland (+299)</option>
                    <option data-countryCode='GD' value='1473'>Grenada (+1473)</option>
                    <option data-countryCode='GP' value='590'>Guadeloupe (+590)</option>
                    <option data-countryCode='GU' value='671'>Guam (+671)</option>
                    <option data-countryCode='GT' value='502'>Guatemala (+502)</option>
                    <option data-countryCode='GN' value='224'>Guinea (+224)</option>
                    <option data-countryCode='GW' value='245'>Guinea - Bissau (+245)</option>
                    <option data-countryCode='GY' value='592'>Guyana (+592)</option>
                    <option data-countryCode='HT' value='509'>Haiti (+509)</option>
                    <option data-countryCode='HN' value='504'>Honduras (+504)</option>
                    <option data-countryCode='HK' value='852'>Hong Kong (+852)</option>
                    <option data-countryCode='HU' value='36'>Hungary (+36)</option>
                    <option data-countryCode='IS' value='354'>Iceland (+354)</option>
                    <option data-countryCode='IN' value='91'>India (+91)</option>
                    <option data-countryCode='ID' value='62'>Indonesia (+62)</option>
                    <option data-countryCode='IR' value='98'>Iran (+98)</option>
                    <option data-countryCode='IQ' value='964'>Iraq (+964)</option>
                    <option data-countryCode='IE' value='353'>Ireland (+353)</option>
                    <option data-countryCode='IL' value='972'>Israel (+972)</option>
                    <option data-countryCode='IT' value='39'>Italy (+39)</option>
                    <option data-countryCode='JM' value='1876'>Jamaica (+1876)</option>
                    <option data-countryCode='JP' value='81'>Japan (+81)</option>
                    <option data-countryCode='JO' value='962'>Jordan (+962)</option>
                    <option data-countryCode='KZ' value='7'>Kazakhstan (+7)</option>
                    <option data-countryCode='KE' value='254'>Kenya (+254)</option>
                    <option data-countryCode='KI' value='686'>Kiribati (+686)</option>
                    <option data-countryCode='KP' value='850'>Korea North (+850)</option>
                    <option data-countryCode='KR' value='82'>Korea South (+82)</option>
                    <option data-countryCode='KW' value='965'>Kuwait (+965)</option>
                    <option data-countryCode='KG' value='996'>Kyrgyzstan (+996)</option>
                    <option data-countryCode='LA' value='856'>Laos (+856)</option>
                    <option data-countryCode='LV' value='371'>Latvia (+371)</option>
                    <option data-countryCode='LB' value='961'>Lebanon (+961)</option>
                    <option data-countryCode='LS' value='266'>Lesotho (+266)</option>
                    <option data-countryCode='LR' value='231'>Liberia (+231)</option>
                    <option data-countryCode='LY' value='218'>Libya (+218)</option>
                    <option data-countryCode='LI' value='417'>Liechtenstein (+417)</option>
                    <option data-countryCode='LT' value='370'>Lithuania (+370)</option>
                    <option data-countryCode='LU' value='352'>Luxembourg (+352)</option>
                    <option data-countryCode='MO' value='853'>Macao (+853)</option>
                    <option data-countryCode='MK' value='389'>Macedonia (+389)</option>
                    <option data-countryCode='MG' value='261'>Madagascar (+261)</option>
                    <option data-countryCode='MW' value='265'>Malawi (+265)</option>
                    <option data-countryCode='MY' value='60'>Malaysia (+60)</option>
                    <option data-countryCode='MV' value='960'>Maldives (+960)</option>
                    <option data-countryCode='ML' value='223'>Mali (+223)</option>
                    <option data-countryCode='MT' value='356'>Malta (+356)</option>
                    <option data-countryCode='MH' value='692'>Marshall Islands (+692)</option>
                    <option data-countryCode='MQ' value='596'>Martinique (+596)</option>
                    <option data-countryCode='MR' value='222'>Mauritania (+222)</option>
                    <option data-countryCode='YT' value='269'>Mayotte (+269)</option>
                    <option data-countryCode='MX' value='52'>Mexico (+52)</option>
                    <option data-countryCode='FM' value='691'>Micronesia (+691)</option>
                    <option data-countryCode='MD' value='373'>Moldova (+373)</option>
                    <option data-countryCode='MC' value='377'>Monaco (+377)</option>
                    <option data-countryCode='MN' value='976'>Mongolia (+976)</option>
                    <option data-countryCode='MS' value='1664'>Montserrat (+1664)</option>
                    <option data-countryCode='MA' value='212'>Morocco (+212)</option>
                    <option data-countryCode='MZ' value='258'>Mozambique (+258)</option>
                    <option data-countryCode='MN' value='95'>Myanmar (+95)</option>
                    <option data-countryCode='NA' value='264'>Namibia (+264)</option>
                    <option data-countryCode='NR' value='674'>Nauru (+674)</option>
                    <option data-countryCode='NP' value='977'>Nepal (+977)</option>
                    <option data-countryCode='NL' value='31'>Netherlands (+31)</option>
                    <option data-countryCode='NC' value='687'>New Caledonia (+687)</option>
                    <option data-countryCode='NZ' value='64'>New Zealand (+64)</option>
                    <option data-countryCode='NI' value='505'>Nicaragua (+505)</option>
                    <option data-countryCode='NE' value='227'>Niger (+227)</option>
                    <option data-countryCode='NG' value='234'>Nigeria (+234)</option>
                    <option data-countryCode='NU' value='683'>Niue (+683)</option>
                    <option data-countryCode='NF' value='672'>Norfolk Islands (+672)</option>
                    <option data-countryCode='NP' value='670'>Northern Marianas (+670)</option>
                    <option data-countryCode='NO' value='47'>Norway (+47)</option>
                    <option data-countryCode='OM' value='968'>Oman (+968)</option>
                    <option data-countryCode='PW' value='680'>Palau (+680)</option>
                    <option data-countryCode='PA' value='507'>Panama (+507)</option>
                    <option data-countryCode='PG' value='675'>Papua New Guinea (+675)</option>
                    <option data-countryCode='PY' value='595'>Paraguay (+595)</option>
                    <option data-countryCode='PK' value='92'>Pakistan (+92)</option>
                    <option data-countryCode='PE' value='51'>Peru (+51)</option>
                    <option data-countryCode='PH' value='63'>Philippines (+63)</option>
                    <option data-countryCode='PL' value='48'>Poland (+48)</option>
                    <option data-countryCode='PT' value='351'>Portugal (+351)</option>
                    <option data-countryCode='PR' value='1787'>Puerto Rico (+1787)</option>
                    <option data-countryCode='QA' value='974'>Qatar (+974)</option>
                    <option data-countryCode='RE' value='262'>Reunion (+262)</option>
                    <option data-countryCode='RO' value='40'>Romania (+40)</option>
                    <option data-countryCode='RU' value='7'>Russia (+7)</option>
                    <option data-countryCode='RW' value='250'>Rwanda (+250)</option>
                    <option data-countryCode='SM' value='378'>San Marino (+378)</option>
                    <option data-countryCode='ST' value='239'>Sao Tome &amp; Principe (+239)</option>
                    <option data-countryCode='SA' value='966'>Saudi Arabia (+966)</option>
                    <option data-countryCode='SN' value='221'>Senegal (+221)</option>
                    <option data-countryCode='CS' value='381'>Serbia (+381)</option>
                    <option data-countryCode='SC' value='248'>Seychelles (+248)</option>
                    <option data-countryCode='SL' value='232'>Sierra Leone (+232)</option>
                    <option data-countryCode='SG' value='65'>Singapore (+65)</option>
                    <option data-countryCode='SK' value='421'>Slovak Republic (+421)</option>
                    <option data-countryCode='SI' value='386'>Slovenia (+386)</option>
                    <option data-countryCode='SB' value='677'>Solomon Islands (+677)</option>
                    <option data-countryCode='SO' value='252'>Somalia (+252)</option>
                    <option data-countryCode='ZA' value='27'>South Africa (+27)</option>
                    <option data-countryCode='ES' value='34'>Spain (+34)</option>
                    <option data-countryCode='LK' value='94'>Sri Lanka (+94)</option>
                    <option data-countryCode='SH' value='290'>St. Helena (+290)</option>
                    <option data-countryCode='KN' value='1869'>St. Kitts (+1869)</option>
                    <option data-countryCode='SC' value='1758'>St. Lucia (+1758)</option>
                    <option data-countryCode='SD' value='249'>Sudan (+249)</option>
                    <option data-countryCode='SR' value='597'>Suriname (+597)</option>
                    <option data-countryCode='SZ' value='268'>Swaziland (+268)</option>
                    <option data-countryCode='SE' value='46'>Sweden (+46)</option>
                    <option data-countryCode='CH' value='41'>Switzerland (+41)</option>
                    <option data-countryCode='SI' value='963'>Syria (+963)</option>
                    <option data-countryCode='TW' value='886'>Taiwan (+886)</option>
                    <option data-countryCode='TJ' value='7'>Tajikstan (+7)</option>
                    <option data-countryCode='TH' value='66'>Thailand (+66)</option>
                    <option data-countryCode='TG' value='228'>Togo (+228)</option>
                    <option data-countryCode='TO' value='676'>Tonga (+676)</option>
                    <option data-countryCode='TT' value='1868'>Trinidad &amp; Tobago (+1868)</option>
                    <option data-countryCode='TN' value='216'>Tunisia (+216)</option>
                    <option data-countryCode='TR' value='90'>Turkey (+90)</option>
                    <option data-countryCode='TM' value='7'>Turkmenistan (+7)</option>
                    <option data-countryCode='TM' value='993'>Turkmenistan (+993)</option>
                    <option data-countryCode='TC' value='1649'>Turks &amp; Caicos Islands (+1649)</option>
                    <option data-countryCode='TV' value='688'>Tuvalu (+688)</option>
                    <option data-countryCode='UG' value='256'>Uganda (+256)</option>
                    <option data-countryCode='GB' value='44'>UK (+44)</option>
                    <option data-countryCode='UA' value='380'>Ukraine (+380)</option>
                    <option data-countryCode='AE' value='971'>United Arab Emirates (+971)</option>
                    <option data-countryCode='UY' value='598'>Uruguay (+598)</option>
                    <option data-countryCode='US' value='1'>USA (+1)</option>
                    <option data-countryCode='UZ' value='7'>Uzbekistan (+7)</option>
                    <option data-countryCode='VU' value='678'>Vanuatu (+678)</option>
                    <option data-countryCode='VA' value='379'>Vatican City (+379)</option>
                    <option data-countryCode='VE' value='58'>Venezuela (+58)</option>
                    <option data-countryCode='VN' value='84'>Vietnam (+84)</option>
                    <option data-countryCode='VG' value='84'>Virgin Islands - British (+1284)</option>
                    <option data-countryCode='VI' value='84'>Virgin Islands - US (+1340)</option>
                    <option data-countryCode='WF' value='681'>Wallis &amp; Futuna (+681)</option>
                    <option data-countryCode='YE' value='969'>Yemen (North)(+969)</option>
                    <option data-countryCode='YE' value='967'>Yemen (South)(+967)</option>
                    <option data-countryCode='ZM' value='260'>Zambia (+260)</option>
                    <option data-countryCode='ZW' value='263'>Zimbabwe (+263)</option>
            </optgroup>
    </select>";
}

function all_models()
{
    $array =
        [
            "attendance"            => "Attendance",
            "category"              => "Category",
            "department"            => "Department",
            "driver"                => "Driver",
            "employee"              => "Employee",
            "evaluation"            => "Evaluation",
            "external_maintenance"  => "External Maintenance",
            "extra_working_hour"    => "Extra Working Hour",
            "gas_fill"              => "Gas Fill",
            "group"                 => "Group",
            "group_store_in"        => "Group Store In",
            "expenses"              => "Petty Cash",
            "items"                 => "Items",
            "item_tracking"         => "Item Tracking",
            "item_used_in"          => "Item Used In",
            "job_card"              => "Job Card",
            "accident_report"       => "Accident Report",
            "rfq"                   => "RFQ",
            "sales"                 => "Sales",
            "settings"              => "Settings",
            "store_location"        => "Store Location",
            "sub_stations_stop"     => "Sub Stations Stop",
            "supplier"              => "Supplier",
            "trip"                  => "Trip",
            "unit"                  => "Unit",
            "user"                  => "User",
            "vacation"              => "Vacation",
            "vehicle"               => "Vehicle",
            "roles"                 => "Roles",
        ];
    return $array;
}

function get_current_user_notifications()
{
    $user_id            = Auth::user()->id;
    $attrs['user_id']   = $user_id;
    $all_notifications = \App\Models\UserNotification::getNotifications([]);
    $seen_notifications_ids = collect(SeenNotification::getSeenNotifications($attrs))->pluck('notification_id')->toArray();

    $unseen_notifications = collect($all_notifications)->filter(function ($notification) use ($seen_notifications_ids) {
        return !in_array($notification->id, $seen_notifications_ids);
    })->sortByDesc('id')->take(5)->values();

    return $unseen_notifications;
}

function get_current_user_notifications_count()
{
    $user_id            = Auth::user()->id;
    $attrs['user_id']   = $user_id;
    $all_notifications  = count(\App\Models\UserNotification::getNotifications([]));
    $seen_notifications = count(SeenNotification::getSeenNotifications($attrs));
    $unseen_notifications = $all_notifications - $seen_notifications;

    return max($unseen_notifications, 0);
}

function check_if_notification_created_before_or_not($model_type, $model_id, $additional_data_name, $additional_data_value)
{

    $additional_data_query = 'additional_data->' . $additional_data_name;

    $notification_obj = UserNotification::where('model_type', $model_type)
                                        ->where('model_id', $model_id)
                                        ->where($additional_data_query, $additional_data_value)
                                        ->first();

    return is_object($notification_obj);
}

/*function create_notifications_in_background()
{
    $current_date = date('Y-m-d');
    $notification_last_run = \App\Models\NotificationRunHistory::where('last_run', $current_date)->first();

    if(!is_object($notification_last_run)){
        \App\Models\NotificationRunHistory::create(['last_run' => $current_date]);
        create_notification_driving_license_of_drivers_will_expire_after_three_month();
        create_notification_driving_license_of_employees_will_expire_after_three_month();
        create_notification_passport_of_employees_will_expire_after_six_month();
        create_notification_insurance_life_of_employees_will_expire_after_one_month();
        create_notification_insurance_medical_of_employees_will_expire_after_one_month();
        create_notification_for_fire_cylinder_will_expire_after_one_month();
        create_notification_for_vehicle_license_no_will_expire_after_one_month();
        create_notification_civil_card_employees_will_expire_after_one_month();
        create_notification_bank_expired_of_employee_will_expire_after_two_month();
        create_notification_id_number_valid_driver_will_expire_after_two_month();
        create_notification_expiry_license_date_mot_vehicles_will_expire_after_two_month();
        create_notification_for_fire_cylinder_2_will_expire_after_one_month();
    }
}*/

function create_notification_driving_license_of_drivers_will_expire_after_three_month()
{
    $current_day = date('Y-m-d');
    $date = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $licenses = Driver::where('license_expired', '<=',$future_date)->get();

    if (!$licenses->isEmpty()) {
        $data   = [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Driver', $item->id, 'license_expired', $item->license_expired)){
                $data[] = [
                    'type'             => NotificationTypes::warning_expired_driving_license->value,
                    'model_type'       => 'App\Models\Driver',
                    'model_id'         => $item->id,
                    'content'          => 'Note: The driving license of driver ( '. $item->name .' ) license no ( '.$item->license_no.' ) will expire in ( '. $item->license_expired . ' ).',
                    'additional_data'  => json_encode(['license_expired' => $item->license_expired]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }
}

function create_notification_driving_license_of_employees_will_expire_after_three_month()
{
    $current_day = date('Y-m-d');
    $date = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $licenses = \App\Models\Employee::where('driving_license_expires_at',  '<=', $future_date)->get();

    if (!$licenses->isEmpty()) {
        $data   = [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'driving_license_expires_at', $item->driving_license_expires_at)){
                $data[] = [
                    'type'             => NotificationTypes::warning_expired_driving_license->value,
                    'model_type'       => 'App\Models\Employee',
                    'model_id'         => $item->id,
                    'content'          => 'Note: The driving license of employee ( '. $item->name .' ) will expire in ( '. $item->driving_license_expires_at . ' ).',
                    'additional_data'  => json_encode(['driving_license_expires_at' => $item->driving_license_expires_at]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }

        }

        UserNotification::insert($data);
    }
}

function create_notification_passport_of_employees_will_expire_after_six_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+6 months');
    $future_date = $date->format('Y-m-d');

    $licenses = \App\Models\Employee::where('passport_expires_at', '<=', $future_date)->get();


    if (!$licenses->isEmpty()) {

        $data   = [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'passport_expires_at', $item->passport_expires_at)) {
                $data[] = [
                    'type'             => NotificationTypes::warning_expired_passport->value,
                    'model_type'       => 'App\Models\Employee',
                    'model_id'         => $item->id,
                    'content'          => 'Note: The passport of employee ( '. $item->name .' ) will expire in ( '. $item->passport_expires_at . ' ).',
                    'additional_data'  => json_encode(['passport_expires_at' => $item->passport_expires_at]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }

}

function create_notification_insurance_life_of_employees_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $licenses = \App\Models\Employee::where('life_expires_at', '<=', $future_date)->get();


    if (!$licenses->isEmpty()) {

        $data= [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'life_expires_at', $item->life_expires_at)) {
                $data[] = [
                    'type'            => NotificationTypes::warning_expired_stay->value,
                    'model_type'      => 'App\Models\Employee',
                    'model_id'        => $item->id,
                    'content'         => 'Note: The insurance life of employee ( ' . $item->name . ' ) will expire in ( ' . $item->life_expires_at . ' ).',
                    'additional_data' => json_encode(['life_expires_at' => $item->life_expires_at]),
                    'created_at'      => now(),
                    'updated_at'      => now(),

                ];
            }

        }

        UserNotification::insert($data);
    }
}

function create_notification_insurance_medical_of_employees_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $licenses = \App\Models\Employee::where('medical_expires_at', '<=', $future_date)->get();


    if (!$licenses->isEmpty()) {
        $data= [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'medical_expires_at', $item->medical_expires_at)) {

                $data[] = [
                    'type'            => NotificationTypes::warning_expired_insurance_medical->value,
                    'model_type'      => 'App\Models\Employee',
                    'model_id'        => $item->id,
                    'content'         => 'Note: The insurance medical of employee ( ' . $item->name . ' ) will expire in ( ' . $item->medical_expires_at . ' ).',
                    'additional_data' => json_encode(['medical_expires_at' => $item->medical_expires_at]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }
}

function create_notification_for_fire_cylinder_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $results = \App\Models\Vehicle::where('fire_cylinder_valid', '<=', $future_date)->get();

    if (!$results->isEmpty()) {

        $data= [];
        foreach ($results as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Vehicle', $item->id, 'fire_cylinder_valid', $item->fire_cylinder_valid)) {
                $data[] = [
                    'type'            => NotificationTypes::warning_expired_fire_cylinder->value,
                    'model_type'      => 'App\Models\Vehicle',
                    'model_id'        => $item->id,
                    'content'         => 'Note: The fire cylinder of vehicle plate no ( ' . $item->plate_no . ' ) will expire in ( ' . $item->fire_cylinder_valid . ' ).',
                    'additional_data' => json_encode(['fire_cylinder_valid' => $item->fire_cylinder_valid]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }
        UserNotification::insert($data);
    }
}

function create_notification_for_vehicle_license_no_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $results = \App\Models\Vehicle::where('license_expiration_date', '<=', $future_date)->get();


    if (!$results->isEmpty()) {

        $data= [];
        foreach ($results as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Vehicle', $item->id, 'license_expiration_date', $item->license_expiration_date)) {

                $data[] = [
                    'type'            => NotificationTypes::warning_expired_vehicle_license->value,
                    'model_type'      => 'App\Models\Vehicle',
                    'model_id'        => $item->id,
                    'content'         => 'Note: Vehicle License ( ' . $item->plate_no . ' ) will expire in ( ' . $item->license_expiration_date . ' ).',
                    'additional_data' => json_encode(['license_expiration_date' => $item->license_expiration_date]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }
        UserNotification::insert($data);
    }

}

function create_notification_civil_card_employees_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $licenses = \App\Models\Employee::where('civil_card_end_date', '<=', $future_date)->get();


    if (!$licenses->isEmpty()) {
        $data= [];
        foreach ($licenses as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'civil_card_end_date', $item->civil_card_end_date)) {

                $data[] = [
                    'type'            => NotificationTypes::warning_civil_card_end_date->value,
                    'model_type'      => 'App\Models\Employee',
                    'model_id'        => $item->id,
                    'content'         => 'Note: The civil card of employee ( ' . $item->name . ' ) will expire in ( ' . $item->civil_card_end_date . ' ).',
                    'additional_data' => json_encode(['civil_card_end_date' => $item->civil_card_end_date]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }


        UserNotification::insert($data);
    }

}

function create_notification_if_item_qty_less_than_min_allowed_value($item_id, $qty_will_decrease)
{


    $item_obj               = \App\Models\Item::where('id', $item_id)->first();
    $new_qty_after_decrease = floatval($item_obj->quantity) - floatval($qty_will_decrease);


    if ($new_qty_after_decrease <= floatval($item_obj->min_allowed_value)) {
        $data = [
            'type'            => NotificationTypes::warning_item_qty_less_than_min_allowed_value->value,
            'model_type'      => 'App\Models\Item',
            'model_id'        => $item_obj->id,
            'content'         => 'Note: Item ( ' . $item_obj->item_name . ' ) - Remaining ( ' . intval($new_qty_after_decrease) . ' ) - Minimum allowed value of item ( ' . $item_obj->min_allowed_value . ' )',
            'additional_data' => json_encode([]),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
        UserNotification::insert($data);

    }
}

function create_notification_bank_expired_of_employee_will_expire_after_two_month()
{
    $current_day = date('Y-m-d');
    $date = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $bank_expired = Employee::where('bank_expired_at', '<=' , $future_date)->get();

    if (!$bank_expired->isEmpty()) {

        $data   = [];
        foreach ($bank_expired as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Employee', $item->id, 'bank_expired_at', $item->bank_expired_at)){
                $data[] = [
                    'type'             => NotificationTypes::warning_bank_expired_at->value,
                    'model_type'       => 'App\Models\Employee',
                    'model_id'         => $item->id,
                    'content'          => 'Note: bank expired at of Employee ( '. $item->name .' ) bank no ( '.$item->bank_acc_no.' ) will expire in ( '. $item->bank_expired_at . ' ).',
                    'additional_data'  => json_encode(['bank_expired_at' => $item->bank_expired_at]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }
}

function create_notification_id_number_valid_driver_will_expire_after_two_month()
{
    $current_day = date('Y-m-d');
    $date = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $idNumValid = Driver::where('id_num_valid', '<=' , $future_date)->get();

    if (!$idNumValid->isEmpty()) {

        $data   = [];
        foreach ($idNumValid as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Driver', $item->id, 'id_num_valid', $item->id_num_valid)){
                $data[] = [
                    'type'             => NotificationTypes::warning_id_num_valid->value,
                    'model_type'       => 'App\Models\Driver',
                    'model_id'         => $item->id,
                    'content'          => 'Note: id number valid of Driver ( '. $item->name .' ) ID Number ( '.$item->passport_no.' ) will expire in ( '. $item->id_num_valid . ' ).',
                    'additional_data'  => json_encode(['id_num_valid' => $item->id_num_valid]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }
}

function create_notification_expiry_license_date_mot_vehicles_will_expire_after_two_month()
{
    $current_day = date('Y-m-d');
    $date = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $expiryLicenseDateMot = \App\Models\Vehicle::where('expiry_license_date_mot', '<=' , $future_date)->get();

    if (!$expiryLicenseDateMot->isEmpty()) {

        $data   = [];
        foreach ($expiryLicenseDateMot as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Vehicle', $item->id, 'expiry_license_date_mot', $item->expiry_license_date_mot)){
                $data[] = [
                    'type'             => NotificationTypes::warning_expiry_license_date_mot->value,
                    'model_type'       => 'App\Models\Vehicle',
                    'model_id'         => $item->id,
                    'content'          => 'Note: expiry license date mot of Vehicle ( '. $item->plate_no .' ) will expire in ( '. $item->expiry_license_date_mot . ' ).',
                    'additional_data'  => json_encode(['expiry_license_date_mot' => $item->expiry_license_date_mot]),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        UserNotification::insert($data);
    }
}

function create_notification_if_part_of_vehicle_need_replacment($plate_no, $colName)
{
    $vehicle_obj              = \App\Models\Vehicle::where('plate_no', $plate_no)->first();

    if ($vehicle_obj) {
        $data = [
            'type'            => NotificationTypes::warning_part_of_vehicle_need_replacement->value,
            'model_type'      => 'App\Models\Vehicle',
            'model_id'        => $vehicle_obj->id,
            'content'         => 'Note: ' . $colName . ' in Vehicle ( ' . $plate_no . ' ) need replacement',
            'additional_data' => json_encode([]),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
        UserNotification::insert($data);
    }
}

function create_notification_for_fire_cylinder_2_will_expire_after_one_month()
{
    $current_day = date('Y-m-d');
    $date        = new DateTime($current_day);
    $date->modify('+1 months');
    $future_date = $date->format('Y-m-d');

    $results = \App\Models\Vehicle::where('fire_cylinder_valid_2', '<=', $future_date)->get();

    if (!$results->isEmpty()) {
        $data= [];
        foreach ($results as $item) {
            if(!check_if_notification_created_before_or_not('App\Models\Vehicle', $item->id, 'fire_cylinder_valid_2', $item->fire_cylinder_valid_2)) {
                $data[] = [
                    'type'            => NotificationTypes::warning_expired_fire_cylinder_valid_2->value,
                    'model_type'      => 'App\Models\Vehicle',
                    'model_id'        => $item->id,
                    'content'         => 'Note: The fire cylinder #2 of vehicle plate no ( ' . $item->plate_no . ' ) will expire in ( ' . $item->fire_cylinder_valid_2 . ' ).',
                    'additional_data' => json_encode(['fire_cylinder_valid_2' => $item->fire_cylinder_valid_2]),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }
        UserNotification::insert($data);
    }
}

function getPermissionsByRole($roleName)
{
    // Find the role by name
    $role = Role::findByName($roleName);

    // Get all permissions associated with the role
    $permissions = $role->permissions;

    return $permissions;
}

function getNotificationPermissionByNotificationType(NotificationTypes $types): ?string
{
    $permissionMap = [
        NotificationTypes::warning_expired_passport->value => 'notification.expired_passport',
        NotificationTypes::warning_expired_driving_license->value => 'notification.expired_driving_license',
        NotificationTypes::warning_expired_stay->value => 'notification.expired_stay',
        NotificationTypes::warning_expired_vehicle_license->value => 'notification.expired_vehicle_license',
        NotificationTypes::warning_expired_fire_cylinder->value => 'notification.expired_fire_cylinder',
        NotificationTypes::warning_expired_insurance_medical->value => 'notification.expired_insurance_medical',
    ];

    return $permissionMap[$types->value] ?? null;
}

function checkNotificationPermissions()
{
    $user = Auth::user();

    /*$notification_permissions = [
        'notification.expired_passport',
        'notification.expired_driving_license',
        'notification.expired_stay',
        'notification.expired_vehicle_license',
        'notification.expired_fire_cylinder',
        'notification.expired_item_qty',
        'notification.expired_civil',
    ];*/

    $notification_permissions = NotificationTypes::notificationTypes();

    $user_permissions = [];
    foreach ($notification_permissions as $permissionEnum) {
        $permissionString = getNotificationPermissionByNotificationType($permissionEnum);
        if ($permissionString && $user->can($permissionString)) {
            $user_permissions[] = $permissionString;
        }
    }

    // If the user has any notification permissions
    if (!empty($user_permissions)) {
        return $user_permissions;
    }

    // If the user has no notification permissions
    Redirect::to('/' . target() . '?error=you do not have any permission to access this page')->send();
    die();
}

function create_notification_if_item_max_distance_less_than_vehicle_estimated_km($item_id, $vehicle_id)
{
    $item_obj = \App\Models\Item::where('id', $item_id)->first();
    $vehicle_obj = \App\Models\Vehicle::where('id', $vehicle_id)->first();

    $data = [
        'type' => NotificationTypes::warning_item_max_distance_less_than_vehicle_estimated_km->value,
        'model_type' => 'App\Models\Item',
        'model_id' => $item_obj->id,
        'content' => 'Note: The Item ( ' . $item_obj->item_name . ' ) - Of Bus ( ' . $vehicle_obj->plate_no . ' ) - Exceeded Max Distance ( ' . $item_obj->max_distance . ' )',
        'additional_data' => json_encode([]),
        'created_at' => now(),
        'updated_at' => now(),
    ];
    UserNotification::insert($data);
}

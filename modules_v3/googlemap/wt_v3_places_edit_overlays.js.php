<?php
// Overlays file for Interface to edit place locations
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id: wt_v3_places_edit_overlays.js.php 12999 2011-12-06 00:23:55Z brian $
?>

// === Create Borders for the UK Countries =========================================================
function overlays() {

	// Define place LatLng arrays
	var polygon1;
	var num_arrays = "";
	if (pl_name == 'Scotland') {
		var returnGeom1 = '-2.02166,55.80611|-2.07972,55.86722|-2.13028,55.88583|-2.26028,55.91861|-2.37528,55.95694|-2.65722,56.05972|-2.82028,56.05694|-2.86618,56.02840|-2.89555,55.98861|-2.93500,55.96944|-3.01805,55.94944|-3.06750,55.94444|-3.25472,55.97166|-3.45472,55.99194|-3.66416,56.00652|-3.73722,56.05555|-3.57139,56.05360|-3.44111,56.01916|-3.39584,56.01083|-3.34403,56.02333|-3.13903,56.11084|-2.97611,56.19472|-2.91666,56.20499|-2.84695,56.18638|-2.78805,56.18749|-2.67937,56.21465|-2.58403,56.28264|-2.67208,56.32277|-2.76861,56.33180|-2.81528,56.37360|-2.81208,56.43958|-2.91653,56.45014|-2.99555,56.41416|-3.19042,56.35958|-3.27805,56.35750|-3.04055,56.45472|-2.95861,56.45611|-2.72084,56.48888|-2.64084,56.52250|-2.53126,56.57611|-2.48861,56.61416|-2.47805,56.71527|-2.39000,56.77166|-2.31986,56.79638|-2.21972,56.86777|-2.19708,56.94388|-2.16695,57.00055|-2.09334,57.07027|-2.05416,57.21861|-1.95889,57.33250|-1.85584,57.39889|-1.77334,57.45805|-1.78139,57.50555|-1.82195,57.57861|-1.86000,57.62138|-1.92972,57.67777|-2.02222,57.69388|-2.07555,57.69944|-2.14028,57.69056|-2.18611,57.66861|-2.39626,57.66638|-2.51000,57.67166|-2.78639,57.70222|-2.89806,57.70694|-2.96750,57.68027|-3.03847,57.66249|-3.12334,57.67166|-3.22334,57.69166|-3.28625,57.72499|-3.33972,57.72333|-3.48805,57.70945|-3.52222,57.66333|-3.59542,57.63666|-3.64063,57.63881|-3.75414,57.62504|-4.03986,57.55569|-4.19666,57.48584|-4.22889,57.51554|-4.17945,57.56249|-4.11139,57.59833|-4.08078,57.66533|-4.19139,57.67139|-4.25945,57.65527|-4.34361,57.60777|-4.41639,57.60166|-4.29666,57.67444|-4.08528,57.72611|-4.01908,57.70226|-3.96861,57.70250|-3.86556,57.76861|-3.81945,57.80458|-3.80681,57.85819|-3.85055,57.82000|-3.92639,57.80749|-4.04322,57.81438|-4.14973,57.82527|-4.29750,57.84638|-4.36250,57.89777|-4.24306,57.87028|-4.10666,57.85195|-4.01500,57.86777|-3.99166,57.90611|-3.99695,57.95056|-3.84500,58.02000|-3.56611,58.13916|-3.51319,58.16374|-3.45916,58.20305|-3.42028,58.24361|-3.33750,58.27694|-3.20555,58.30625|-3.10972,58.38166|-3.05792,58.45083|-3.02264,58.64653|-3.17639,58.64944|-3.35389,58.66055|-3.36931,58.59555|-3.57611,58.62194|-3.66028,58.61972|-3.71166,58.60374|-3.78264,58.56750|-3.84834,58.56000|-4.08056,58.55527|-4.27722,58.53361|-4.43653,58.54902|-4.50666,58.56777|-4.56055,58.57584|-4.59910,58.53027|-4.66805,58.48833|-4.76146,58.44604|-4.70195,58.50999|-4.70166,58.55861|-4.77014,58.60264|-5.00153,58.62416|-5.10945,58.50833|-5.16472,58.32527|-5.12639,58.28750|-5.07166,58.26472|-5.20361,58.25083|-5.39764,58.25055|-5.27389,58.11722|-5.31514,58.06416|-5.38416,58.08361|-5.45285,58.07416|-5.39805,58.03111|-5.26278,57.97111|-5.19334,57.95069|-5.12750,57.86944|-5.21750,57.90084|-5.33861,57.92083|-5.42876,57.90104|-5.45750,57.85889|-5.64445,57.89972|-5.62555,57.85222|-5.58153,57.81945|-5.60674,57.76618|-5.66305,57.78889|-5.71695,57.86944|-5.76695,57.86472|-5.81708,57.81944|-5.81084,57.63958|-5.69555,57.55944|-5.64361,57.55222|-5.53084,57.52833|-5.65305,57.50875|-5.75000,57.54834|-5.81569,57.57923|-5.85042,57.54972|-5.86695,57.46777|-5.81806,57.36250|-5.75111,57.34333|-5.50334,57.40111|-5.45126,57.41805|-5.49250,57.37083|-5.59884,57.33049|-5.57116,57.28411|-5.51266,57.27745|-5.40514,57.23097|-5.44972,57.22138|-5.49472,57.23888|-5.56066,57.25477|-5.64611,57.23499|-5.64751,57.16161|-5.55028,57.11639|-5.48166,57.11222|-5.40305,57.11062|-5.55945,57.09250|-5.65111,57.11611|-5.72472,57.11306|-5.77361,57.04556|-5.63139,56.98499|-5.56916,56.98972|-5.52403,56.99735|-5.57916,56.98000|-5.64611,56.97222|-5.73374,57.00909|-5.82584,57.00346|-5.91958,56.88708|-5.86528,56.87944|-5.74278,56.89374|-5.66292,56.86924|-5.73306,56.83916|-5.78584,56.83955|-5.85590,56.81430|-5.80208,56.79180|-5.84958,56.74444|-5.90500,56.75666|-5.96694,56.78027|-6.14000,56.75777|-6.19208,56.74888|-6.23452,56.71673|-6.19139,56.67972|-5.91916,56.67388|-5.82622,56.69156|-5.73945,56.71166|-5.55240,56.68886|-5.64861,56.68027|-5.69916,56.68278|-5.88261,56.65666|-5.97472,56.65138|-5.99584,56.61138|-5.93056,56.56972|-5.88416,56.55333|-5.79056,56.53805|-5.67695,56.49389|-5.56389,56.54056|-5.36334,56.66195|-5.23416,56.74333|-5.13236,56.79403|-5.31473,56.65666|-5.37405,56.55925|-5.31826,56.55633|-5.25080,56.55753|-5.37718,56.52112|-5.39866,56.47866|-5.19111,56.46194|-5.11556,56.51277|-5.07014,56.56069|-5.13555,56.48499|-5.22084,56.43583|-5.32764,56.43574|-5.42439,56.43091|-5.52611,56.37360|-5.57139,56.32833|-5.59653,56.25695|-5.57389,56.16000|-5.52000,56.16485|-5.56334,56.11333|-5.60139,56.07638|-5.64222,56.04305|-5.66039,55.98263|-5.62555,56.02055|-5.58014,56.01319|-5.63361,55.96611|-5.67697,55.88844|-5.64750,55.78139|-5.60986,55.75930|-5.66916,55.66166|-5.70166,55.58861|-5.71805,55.51500|-5.75916,55.41750|-5.79528,55.36027|-5.78166,55.29902|-5.73778,55.29222|-5.56694,55.31666|-5.51528,55.36347|-5.55520,55.41440|-5.48639,55.64306|-5.44597,55.70680|-5.38000,55.75027|-5.41889,55.90666|-5.39924,55.99972|-5.33895,56.03456|-5.30594,56.06922|-5.23889,56.11889|-5.03222,56.23250|-4.92229,56.27111|-4.97416,56.23333|-5.07222,56.18695|-5.20069,56.11861|-5.30906,56.00570|-5.34000,55.90201|-5.29250,55.84750|-5.20805,55.84444|-5.22458,55.90175|-5.17334,55.92916|-5.11000,55.90306|-5.01222,55.86694|-4.96195,55.88000|-4.89824,55.98145|-4.84623,56.08632|-4.86636,56.03178|-4.85461,55.98648|-4.77659,55.97977|-4.62723,55.94555|-4.52305,55.91861|-4.70972,55.93403|-4.75166,55.94611|-4.82406,55.94950|-4.87826,55.93653|-4.91639,55.70083|-4.87584,55.68194|-4.81361,55.64555|-4.68722,55.59750|-4.61361,55.49069|-4.63958,55.44264|-4.68250,55.43388|-4.74847,55.41055|-4.83715,55.31882|-4.84778,55.26944|-4.86542,55.22340|-4.93500,55.17860|-5.01250,55.13347|-5.05361,55.04902|-5.17834,54.98888|-5.18563,54.93622|-5.17000,54.89111|-5.11666,54.83180|-5.00500,54.76333|-4.96229,54.68125|-4.92250,54.64055|-4.85723,54.62958|-4.96076,54.79687|-4.92431,54.83708|-4.85222,54.86861|-4.80125,54.85556|-4.74055,54.82166|-4.68084,54.79972|-4.59861,54.78027|-4.55792,54.73903|-4.49639,54.69888|-4.37584,54.67666|-4.34569,54.70916|-4.35973,54.77111|-4.41111,54.82583|-4.42445,54.88152|-4.38479,54.90555|-4.35056,54.85903|-4.09555,54.76777|-3.95361,54.76749|-3.86972,54.80527|-3.81222,54.84888|-3.69250,54.88110|-3.61584,54.87527|-3.57111,54.99083|-3.44528,54.98638|-3.36056,54.97138|-3.14695,54.96500|-3.05103,54.97986|-3.01500,55.05222|-2.96278,55.03889|-2.69945,55.17722|-2.63055,55.25500|-2.46305,55.36111|-2.21236,55.42777|-2.18278,55.45985|-2.21528,55.50583|-2.27416,55.57527|-2.27916,55.64472|-2.22000,55.66499|-2.08361,55.78054|-2.02166,55.80611'; 
		num_arrays = 1;
	} else if (pl_name == 'England') {
		// England
		var returnGeom1 = '-4.74361,50.66750|-4.78361,50.59361|-4.91584,50.57722|-5.01750,50.54264|-5.02569,50.47271|-5.04729,50.42750|-5.15208,50.34374|-5.26805,50.27389|-5.43194,50.19326|-5.49584,50.21695|-5.54639,50.20527|-5.71000,50.12916|-5.71681,50.06083|-5.66174,50.03631|-5.58278,50.04777|-5.54166,50.07055|-5.53416,50.11569|-5.47055,50.12499|-5.33361,50.09138|-5.27666,50.05972|-5.25674,50.00514|-5.19306,49.95527|-5.16070,50.00319|-5.06555,50.03750|-5.07090,50.08166|-5.04806,50.17111|-4.95278,50.19333|-4.85750,50.23166|-4.76250,50.31138|-4.67861,50.32583|-4.54334,50.32222|-4.48278,50.32583|-4.42972,50.35139|-4.38000,50.36388|-4.16555,50.37028|-4.11139,50.33027|-4.05708,50.29791|-3.94389,50.31346|-3.87764,50.28139|-3.83653,50.22972|-3.78944,50.21222|-3.70666,50.20972|-3.65195,50.23111|-3.55139,50.43833|-3.49416,50.54639|-3.46181,50.58792|-3.41139,50.61610|-3.24416,50.67444|-3.17347,50.68833|-3.09445,50.69222|-2.97806,50.70638|-2.92750,50.73125|-2.88278,50.73111|-2.82305,50.72027|-2.77139,50.70861|-2.66195,50.67334|-2.56305,50.63222|-2.45861,50.57500|-2.44666,50.62639|-2.39097,50.64166|-2.19722,50.62611|-2.12195,50.60722|-2.05445,50.58569|-1.96437,50.59674|-1.95441,50.66536|-2.06681,50.71430|-1.93416,50.71277|-1.81639,50.72306|-1.68445,50.73888|-1.59278,50.72416|-1.33139,50.79138|-1.11695,50.80694|-1.15889,50.84083|-1.09445,50.84584|-0.92842,50.83966|-0.86584,50.79965|-0.90826,50.77396|-0.78187,50.72722|-0.74611,50.76583|-0.67528,50.78111|-0.57722,50.79527|-0.25500,50.82638|-0.19084,50.82583|-0.13805,50.81833|0.05695,50.78083|0.12334,50.75944|0.22778,50.73944|0.28695,50.76500|0.37195,50.81638|0.43084,50.83111|0.56722,50.84777|0.67889,50.87681|0.71639,50.90500|0.79334,50.93610|0.85666,50.92556|0.97125,50.98111|0.99778,51.01903|1.04555,51.04944|1.10028,51.07361|1.26250,51.10166|1.36889,51.13583|1.41111,51.20111|1.42750,51.33111|1.38556,51.38777|1.19195,51.37861|1.05278,51.36722|0.99916,51.34777|0.90806,51.34069|0.70416,51.37749|0.61972,51.38304|0.55945,51.40596|0.64236,51.44042|0.69750,51.47084|0.59195,51.48777|0.53611,51.48806|0.48916,51.48445|0.45215,51.45562|0.38894,51.44822|0.46500,51.50306|0.65195,51.53680|0.76695,51.52138|0.82084,51.53556|0.87528,51.56110|0.95250,51.60923|0.94695,51.72556|0.90257,51.73465|0.86306,51.71166|0.76140,51.69164|0.70111,51.71847|0.86211,51.77361|0.93236,51.80583|0.98278,51.82527|1.03569,51.77416|1.08834,51.77056|1.13222,51.77694|1.18139,51.78972|1.22361,51.80888|1.26611,51.83916|1.28097,51.88096|1.20834,51.95083|1.16347,52.02361|1.27750,51.98555|1.33125,51.92875|1.39028,51.96999|1.58736,52.08388|1.63000,52.19527|1.68576,52.32630|1.73028,52.41138|1.74945,52.45583|1.74590,52.62021|1.70250,52.71583|1.64528,52.77111|1.50361,52.83749|1.43222,52.87472|1.35250,52.90972|1.28222,52.92750|1.18389,52.93889|0.99472,52.95111|0.94222,52.95083|0.88472,52.96638|0.66722,52.97611|0.54778,52.96618|0.49139,52.93430|0.44431,52.86569|0.42903,52.82403|0.36334,52.78027|0.21778,52.80694|0.16125,52.86250|0.05778,52.88916|0.00211,52.87985|0.03222,52.91722|0.20389,53.02805|0.27666,53.06694|0.33916,53.09236|0.35389,53.18722|0.33958,53.23472|0.23555,53.39944|0.14347,53.47527|0.08528,53.48638|0.02694,53.50972|-0.10084,53.57306|-0.20722,53.63083|-0.26445,53.69083|-0.30166,53.71319|-0.39022,53.70794|-0.51972,53.68527|-0.71653,53.69638|-0.65445,53.72527|-0.60584,53.72972|-0.54916,53.70611|-0.42261,53.71755|-0.35728,53.73056|-0.29389,53.73666|-0.23139,53.72166|-0.10584,53.63166|-0.03472,53.62555|0.04416,53.63916|0.08916,53.62666|0.14945,53.58847|0.12639,53.64527|0.06264,53.70389|-0.12750,53.86388|-0.16916,53.91847|-0.21222,54.00833|-0.20569,54.05153|-0.16111,54.08806|-0.11694,54.13222|-0.20053,54.15171|-0.26250,54.17444|-0.39334,54.27277|-0.42166,54.33222|-0.45750,54.37694|-0.51847,54.44749|-0.56472,54.48000|-0.87584,54.57027|-1.06139,54.61722|-1.16528,54.64972|-1.30445,54.77138|-1.34556,54.87138|-1.41278,54.99944|-1.48292,55.08625|-1.51500,55.14972|-1.56584,55.28722|-1.58097,55.48361|-1.63597,55.58194|-1.69000,55.60556|-1.74695,55.62499|-1.81764,55.63306|-1.97681,55.75416|-2.02166,55.80611|-2.08361,55.78054|-2.22000,55.66499|-2.27916,55.64472|-2.27416,55.57527|-2.21528,55.50583|-2.18278,55.45985|-2.21236,55.42777|-2.46305,55.36111|-2.63055,55.25500|-2.69945,55.17722|-2.96278,55.03889|-3.01500,55.05222|-3.05103,54.97986|-3.13292,54.93139|-3.20861,54.94944|-3.28931,54.93792|-3.39166,54.87639|-3.42916,54.81555|-3.56916,54.64249|-3.61306,54.48861|-3.49305,54.40333|-3.43389,54.34806|-3.41056,54.28014|-3.38055,54.24444|-3.21472,54.09555|-3.15222,54.08194|-2.93097,54.15333|-2.81361,54.22277|-2.81750,54.14277|-2.83361,54.08500|-2.93250,53.95055|-3.05264,53.90764|-3.03708,53.74944|-2.99278,53.73277|-2.89979,53.72499|-2.97729,53.69382|-3.07306,53.59805|-3.10563,53.55993|-3.00678,53.41738|-2.95389,53.36027|-2.85736,53.32083|-2.70493,53.35062|-2.77639,53.29250|-2.89972,53.28916|-2.94250,53.31056|-3.02889,53.38191|-3.07248,53.40936|-3.16695,53.35708|-3.12611,53.32500|-3.08860,53.26001|-3.02000,53.24722|-2.95528,53.21555|-2.91069,53.17014|-2.89389,53.10416|-2.85695,53.03249|-2.77792,52.98514|-2.73109,52.96873|-2.71945,52.91902|-2.79278,52.90207|-2.85069,52.93875|-2.99389,52.95361|-3.08639,52.91611|-3.13014,52.88486|-3.13708,52.79312|-3.06806,52.77027|-3.01111,52.71166|-3.06666,52.63527|-3.11750,52.58666|-3.07089,52.55702|-3.00792,52.56902|-2.98028,52.53083|-3.02736,52.49792|-3.11916,52.49194|-3.19514,52.46722|-3.19611,52.41027|-3.02195,52.34027|-2.95486,52.33117|-2.99750,52.28139|-3.05125,52.23347|-3.07555,52.14804|-3.12222,52.11805|-3.11250,52.06945|-3.08500,52.01930|-3.04528,51.97639|-2.98889,51.92555|-2.91757,51.91569|-2.86639,51.92889|-2.77861,51.88583|-2.65944,51.81806|-2.68334,51.76957|-2.68666,51.71889|-2.66500,51.61500|-2.62916,51.64416|-2.57889,51.67777|-2.46056,51.74666|-2.40389,51.74041|-2.47166,51.72445|-2.55305,51.65722|-2.65334,51.56389|-2.77055,51.48916|-2.85278,51.44472|-2.96000,51.37499|-3.00695,51.30722|-3.01278,51.25632|-3.02834,51.20611|-3.30139,51.18111|-3.39361,51.18138|-3.43729,51.20638|-3.50722,51.22333|-3.57014,51.23027|-3.63222,51.21805|-3.70028,51.23000|-3.79250,51.23916|-3.88389,51.22416|-3.98472,51.21695|-4.11666,51.21222|-4.22805,51.18777|-4.22028,51.11054|-4.23702,51.04659|-4.30361,51.00416|-4.37639,50.99110|-4.42736,51.00958|-4.47445,51.01416|-4.52132,51.01424|-4.54334,50.92694|-4.56139,50.77625|-4.65139,50.71527|-4.74361,50.66750'; //|-3.08860,53.26001|-3.33639,53.34722|-3.38806,53.34361|-3.60986,53.27944|-3.73014,53.28944|-3.85445,53.28444|-4.01861,53.23750|-4.06639,53.22639|-4.15334,53.22556|-4.19639,53.20611|-4.33028,53.11222|-4.36097,53.02888|-4.55278,52.92889|-4.61889,52.90916|-4.72195,52.83611|-4.72778,52.78139|-4.53945,52.79306|-4.47722,52.85500|-4.41416,52.88472|-4.31292,52.90499|-4.23334,52.91499|-4.13569,52.87888|-4.13056,52.77777|-4.05334,52.71666|-4.10639,52.65084|-4.12597,52.60375|-4.08056,52.55333|-4.05972,52.48584|-4.09666,52.38583|-4.14305,52.32027|-4.19361,52.27638|-4.23166,52.24888|-4.52722,52.13083|-4.66945,52.13027|-4.73695,52.10361|-4.76778,52.06444|-4.84445,52.01388|-5.09945,51.96056|-5.23916,51.91638|-5.25889,51.87056|-5.18500,51.86958|-5.11528,51.83333|-5.10257,51.77895|-5.16111,51.76222|-5.24694,51.73027|-5.19111,51.70888|-5.00739,51.70349|-4.90875,51.71249|-4.86111,51.71334|-4.97061,51.67577|-5.02128,51.66861|-5.05139,51.62028|-5.00528,51.60638|-4.94139,51.59416|-4.89028,51.62694|-4.83569,51.64534|-4.79063,51.63340|-4.69028,51.66666|-4.64584,51.72666|-4.57445,51.73416|-4.43611,51.73722|-4.26222,51.67694|-4.19750,51.67916|-4.06614,51.66804|-4.11639,51.63416|-4.17750,51.62235|-4.25055,51.62861|-4.29208,51.60743|-4.27778,51.55666|-4.20486,51.53527|-3.94972,51.61278|-3.83792,51.61999|-3.78166,51.56750|-3.75160,51.52931|-3.67194,51.47388|-3.54250,51.39777|-3.40334,51.37972|-3.27097,51.38014|-3.16458,51.40909|-3.15166,51.45305|-3.11875,51.48750|-3.02111,51.52527|-2.95472,51.53972|-2.89278,51.53861|-2.84778,51.54500|-2.71472,51.58083|-2.66500,51.61500|-2.68666,51.71889|-2.68334,51.76957|-2.65944,51.81806|-2.77861,51.88583|-2.86639,51.92889|-2.91757,51.91569|-2.98889,51.92555|-3.04528,51.97639|-3.08500,52.01930|-3.11250,52.06945|-3.12222,52.11805|-3.07555,52.14804|-3.05125,52.23347|-2.99750,52.28139|-2.95486,52.33117|-3.02195,52.34027|-3.19611,52.41027|-3.19514,52.46722|-3.11916,52.49194|-3.02736,52.49792|-2.98028,52.53083|-3.00792,52.56902|-3.07089,52.55702|-3.11750,52.58666|-3.06666,52.63527|-3.01111,52.71166|-3.06806,52.77027|-3.13708,52.79312|-3.13014,52.88486|-3.08639,52.91611|-2.99389,52.95361|-2.85069,52.93875|-2.79278,52.90207|-2.71945,52.91902|-2.73109,52.96873|-2.77792,52.98514|-2.85695,53.03249|-2.89389,53.10416|-2.91069,53.17014|-2.95528,53.21555|-3.02000,53.24722|-3.08860,53.26001'; 
		// Wales Test 
//		var returnGeom2 = '-3.08860,53.26001|-3.33639,53.34722|-3.38806,53.34361|-3.60986,53.27944|-3.73014,53.28944|-3.85445,53.28444|-4.01861,53.23750|-4.06639,53.22639|-4.15334,53.22556|-4.19639,53.20611|-4.33028,53.11222|-4.36097,53.02888|-4.55278,52.92889|-4.61889,52.90916|-4.72195,52.83611|-4.72778,52.78139|-4.53945,52.79306|-4.47722,52.85500|-4.41416,52.88472|-4.31292,52.90499|-4.23334,52.91499|-4.13569,52.87888|-4.13056,52.77777|-4.05334,52.71666|-4.10639,52.65084|-4.12597,52.60375|-4.08056,52.55333|-4.05972,52.48584|-4.09666,52.38583|-4.14305,52.32027|-4.19361,52.27638|-4.23166,52.24888|-4.52722,52.13083|-4.66945,52.13027|-4.73695,52.10361|-4.76778,52.06444|-4.84445,52.01388|-5.09945,51.96056|-5.23916,51.91638|-5.25889,51.87056|-5.18500,51.86958|-5.11528,51.83333|-5.10257,51.77895|-5.16111,51.76222|-5.24694,51.73027|-5.19111,51.70888|-5.00739,51.70349|-4.90875,51.71249|-4.86111,51.71334|-4.97061,51.67577|-5.02128,51.66861|-5.05139,51.62028|-5.00528,51.60638|-4.94139,51.59416|-4.89028,51.62694|-4.83569,51.64534|-4.79063,51.63340|-4.69028,51.66666|-4.64584,51.72666|-4.57445,51.73416|-4.43611,51.73722|-4.26222,51.67694|-4.19750,51.67916|-4.06614,51.66804|-4.11639,51.63416|-4.17750,51.62235|-4.25055,51.62861|-4.29208,51.60743|-4.27778,51.55666|-4.20486,51.53527|-3.94972,51.61278|-3.83792,51.61999|-3.78166,51.56750|-3.75160,51.52931|-3.67194,51.47388|-3.54250,51.39777|-3.40334,51.37972|-3.27097,51.38014|-3.16458,51.40909|-3.15166,51.45305|-3.11875,51.48750|-3.02111,51.52527|-2.95472,51.53972|-2.89278,51.53861|-2.84778,51.54500|-2.71472,51.58083|-2.66500,51.61500|-2.68666,51.71889|-2.68334,51.76957|-2.65944,51.81806|-2.77861,51.88583|-2.86639,51.92889|-2.91757,51.91569|-2.98889,51.92555|-3.04528,51.97639|-3.08500,52.01930|-3.11250,52.06945|-3.12222,52.11805|-3.07555,52.14804|-3.05125,52.23347|-2.99750,52.28139|-2.95486,52.33117|-3.02195,52.34027|-3.19611,52.41027|-3.19514,52.46722|-3.11916,52.49194|-3.02736,52.49792|-2.98028,52.53083|-3.00792,52.56902|-3.07089,52.55702|-3.11750,52.58666|-3.06666,52.63527|-3.01111,52.71166|-3.06806,52.77027|-3.13708,52.79312|-3.13014,52.88486|-3.08639,52.91611|-2.99389,52.95361|-2.85069,52.93875|-2.79278,52.90207|-2.71945,52.91902|-2.73109,52.96873|-2.77792,52.98514|-2.85695,53.03249|-2.89389,53.10416|-2.91069,53.17014|-2.95528,53.21555|-3.02000,53.24722|-3.08860,53.26001'; 
		num_arrays = 2;
	} else if (pl_name == 'Wales') {
		var returnGeom1 = '-3.08860,53.26001|-3.33639,53.34722|-3.38806,53.34361|-3.60986,53.27944|-3.73014,53.28944|-3.85445,53.28444|-4.01861,53.23750|-4.06639,53.22639|-4.15334,53.22556|-4.19639,53.20611|-4.33028,53.11222|-4.36097,53.02888|-4.55278,52.92889|-4.61889,52.90916|-4.72195,52.83611|-4.72778,52.78139|-4.53945,52.79306|-4.47722,52.85500|-4.41416,52.88472|-4.31292,52.90499|-4.23334,52.91499|-4.13569,52.87888|-4.13056,52.77777|-4.05334,52.71666|-4.10639,52.65084|-4.12597,52.60375|-4.08056,52.55333|-4.05972,52.48584|-4.09666,52.38583|-4.14305,52.32027|-4.19361,52.27638|-4.23166,52.24888|-4.52722,52.13083|-4.66945,52.13027|-4.73695,52.10361|-4.76778,52.06444|-4.84445,52.01388|-5.09945,51.96056|-5.23916,51.91638|-5.25889,51.87056|-5.18500,51.86958|-5.11528,51.83333|-5.10257,51.77895|-5.16111,51.76222|-5.24694,51.73027|-5.19111,51.70888|-5.00739,51.70349|-4.90875,51.71249|-4.86111,51.71334|-4.97061,51.67577|-5.02128,51.66861|-5.05139,51.62028|-5.00528,51.60638|-4.94139,51.59416|-4.89028,51.62694|-4.83569,51.64534|-4.79063,51.63340|-4.69028,51.66666|-4.64584,51.72666|-4.57445,51.73416|-4.43611,51.73722|-4.26222,51.67694|-4.19750,51.67916|-4.06614,51.66804|-4.11639,51.63416|-4.17750,51.62235|-4.25055,51.62861|-4.29208,51.60743|-4.27778,51.55666|-4.20486,51.53527|-3.94972,51.61278|-3.83792,51.61999|-3.78166,51.56750|-3.75160,51.52931|-3.67194,51.47388|-3.54250,51.39777|-3.40334,51.37972|-3.27097,51.38014|-3.16458,51.40909|-3.15166,51.45305|-3.11875,51.48750|-3.02111,51.52527|-2.95472,51.53972|-2.89278,51.53861|-2.84778,51.54500|-2.71472,51.58083|-2.66500,51.61500|-2.68666,51.71889|-2.68334,51.76957|-2.65944,51.81806|-2.77861,51.88583|-2.86639,51.92889|-2.91757,51.91569|-2.98889,51.92555|-3.04528,51.97639|-3.08500,52.01930|-3.11250,52.06945|-3.12222,52.11805|-3.07555,52.14804|-3.05125,52.23347|-2.99750,52.28139|-2.95486,52.33117|-3.02195,52.34027|-3.19611,52.41027|-3.19514,52.46722|-3.11916,52.49194|-3.02736,52.49792|-2.98028,52.53083|-3.00792,52.56902|-3.07089,52.55702|-3.11750,52.58666|-3.06666,52.63527|-3.01111,52.71166|-3.06806,52.77027|-3.13708,52.79312|-3.13014,52.88486|-3.08639,52.91611|-2.99389,52.95361|-2.85069,52.93875|-2.79278,52.90207|-2.71945,52.91902|-2.73109,52.96873|-2.77792,52.98514|-2.85695,53.03249|-2.89389,53.10416|-2.91069,53.17014|-2.95528,53.21555|-3.02000,53.24722|-3.08860,53.26001'; 
		num_arrays = 1;
	} else if (pl_name == 'Ireland') {
		var returnGeom1 = '-8.17166,54.46388|-8.06555,54.37277|-7.94139,54.29944|-7.87576,54.28499|-7.86834,54.22764|-7.81805,54.19916|-7.69972,54.20250|-7.55945,54.12694|-7.31334,54.11250|-7.14584,54.22527|-7.17555,54.28916|-7.16084,54.33666|-7.05834,54.41000|-6.97445,54.40166|-6.92695,54.37916|-6.87305,54.34208|-6.85111,54.28972|-6.73473,54.18361|-6.65556,54.06527|-6.60584,54.04444|-6.44750,54.05833|-6.33889,54.11555|-6.26697,54.09983|-6.17403,54.07222|-6.10834,54.03638|-6.04389,54.03139|-5.96834,54.06389|-5.88500,54.11639|-5.87347,54.20916|-5.82500,54.23958|-5.74611,54.24806|-5.65556,54.22701|-5.60834,54.24972|-5.55916,54.29084|-5.57334,54.37704|-5.64502,54.49267|-5.70472,54.53361|-5.68055,54.57306|-5.59972,54.54194|-5.55097,54.50083|-5.54216,54.44903|-5.54643,54.40527|-5.50672,54.36444|-5.46111,54.38555|-5.43132,54.48596|-5.47945,54.53638|-5.53521,54.65090|-5.57431,54.67722|-5.62916,54.67945|-5.73674,54.67383|-5.80305,54.66138|-5.88257,54.60652|-5.92445,54.63180|-5.86681,54.68972|-5.81903,54.70972|-5.74672,54.72452|-5.68775,54.76335|-5.70931,54.83166|-5.74694,54.85361|-5.79139,54.85139|-6.03611,55.05778|-6.04250,55.10277|-6.03444,55.15458|-6.10125,55.20945|-6.14584,55.22069|-6.25500,55.21194|-6.37639,55.23916|-6.51556,55.23305|-6.61334,55.20722|-6.73028,55.18027|-6.82472,55.16806|-6.88972,55.16777|-6.96695,55.15611|-6.99416,55.11027|-7.05139,55.04680|-7.09500,55.03694|-7.25251,55.07059|-7.32639,55.04527|-7.40639,54.95333|-7.45805,54.85777|-7.55334,54.76277|-7.73916,54.71054|-7.82576,54.73416|-7.92639,54.70054|-7.85236,54.63388|-7.77750,54.62694|-7.83361,54.55389|-7.95084,54.53222|-8.04695,54.50722|-8.17166,54.46388'; 
		num_arrays = 1;
	} else if (pl_name == 'NC') {
		var returnGeom1 = '-81.65876,36.60938|-81.70390,36.55513|-81.70639,36.50804|-81.74665,36.39777|-81.90723,36.30804|-82.03195,36.12694|-82.08416,36.10146|-82.12826,36.11020|-82.21500,36.15833|-82.36375,36.11347|-82.43472,36.06013|-82.46236,36.01708|-82.56006,35.96263|-82.60042,35.99638|-82.62308,36.06121|-82.73500,36.01833|-82.84612,35.94944|-82.90451,35.88819|-82.93555,35.83846|-83.16000,35.76236|-83.24222,35.71944|-83.49222,35.57111|-83.56847,35.55861|-83.64416,35.56471|-83.73499,35.56638|-83.88222,35.51791|-83.98361,35.44944|-84.03639,35.35444|-84.04964,35.29117|-84.09042,35.25986|-84.15084,35.25388|-84.20521,35.25722|-84.29284,35.22596|-84.32471,34.98701|-83.09778,35.00027|-82.77722,35.09138|-82.59639,35.14972|-82.37999,35.21500|-82.27362,35.20583|-81.41306,35.17416|-81.05915,35.15333|-80.92666,35.10695|-80.78751,34.95610|-80.79334,34.82555|-79.66777,34.80694|-79.11555,34.34527|-78.57222,33.88166|-78.51806,33.87999|-78.43721,33.89804|-78.23735,33.91986|-78.15389,33.91471|-78.06974,33.89500|-78.02597,33.88936|-77.97611,33.94276|-77.95299,33.99243|-77.94499,34.06499|-77.92728,34.11756|-77.92250,33.99194|-77.92264,33.93715|-77.88215,34.06166|-77.86222,34.15083|-77.83501,34.19194|-77.75724,34.28527|-77.68222,34.36555|-77.63667,34.39805|-77.57363,34.43694|-77.45527,34.50403|-77.38173,34.51646|-77.37905,34.56294|-77.38572,34.61260|-77.40944,34.68916|-77.38847,34.73304|-77.33097,34.63992|-77.35024,34.60099|-77.30958,34.55972|-77.09424,34.67742|-76.75994,34.76659|-76.68325,34.79749|-76.66097,34.75781|-76.62611,34.71014|-76.50063,34.73617|-76.48138,34.77638|-76.38305,34.86423|-76.34326,34.88194|-76.27181,34.96263|-76.35125,35.02221|-76.32354,34.97429|-76.45319,34.93524|-76.43395,34.98782|-76.45356,35.06676|-76.52917,35.00444|-76.63382,34.98242|-76.69722,34.94887|-76.75306,34.90526|-76.81636,34.93944|-76.89000,34.95388|-76.93180,34.96957|-76.96501,34.99777|-77.06816,35.14978|-76.97639,35.06806|-76.86722,35.00000|-76.80531,34.98559|-76.72708,35.00152|-76.60402,35.07416|-76.56555,35.11486|-76.57305,35.16013|-76.66489,35.16694|-76.56361,35.23361|-76.48750,35.22582|-76.46889,35.27166|-76.50298,35.30791|-76.83251,35.39222|-77.02305,35.48694|-77.04958,35.52694|-76.91292,35.46166|-76.65250,35.41499|-76.61611,35.45888|-76.63195,35.52249|-76.58820,35.55104|-76.51556,35.53194|-76.56711,35.48494|-76.52251,35.40416|-76.46195,35.37221|-76.13319,35.35986|-76.04111,35.42416|-76.00223,35.46610|-75.97958,35.51666|-75.89362,35.57555|-75.83834,35.56694|-75.78944,35.57138|-75.74076,35.61846|-75.72084,35.69263|-75.72084,35.81451|-75.74917,35.87791|-75.78333,35.91972|-75.85083,35.97527|-75.94333,35.91777|-75.98944,35.88054|-75.98854,35.79110|-75.99388,35.71027|-76.02875,35.65409|-76.10320,35.66041|-76.13563,35.69239|-76.04475,35.68436|-76.04167,35.74916|-76.05305,35.79361|-76.05305,35.87375|-76.02653,35.96222|-76.07751,35.99319|-76.17472,35.99596|-76.27917,35.91915|-76.37986,35.95763|-76.42014,35.97874|-76.55375,35.93971|-76.66222,35.93305|-76.72952,35.93984|-76.73392,36.04760|-76.75384,36.09477|-76.76028,36.14513|-76.74610,36.22818|-76.70458,36.24673|-76.72764,36.16736|-76.71021,36.11752|-76.69117,36.07165|-76.65979,36.03312|-76.49527,36.00958|-76.37138,36.07694|-76.37084,36.14999|-76.21417,36.09471|-76.07591,36.17910|-76.18361,36.26915|-76.19965,36.31739|-76.13986,36.28805|-76.04274,36.21974|-76.00465,36.18110|-75.95287,36.19241|-75.97604,36.31138|-75.93895,36.28381|-75.85271,36.11069|-75.79315,36.07385|-75.79639,36.11804|-75.88333,36.29554|-75.94665,36.37194|-75.98694,36.41166|-76.03473,36.49666|-76.02899,36.55000|-78.44234,36.54986|-78.56594,36.55799|-80.27556,36.55110|-81.15361,36.56499|-81.38722,36.57695|-81.65876,36.60938'; 
		num_arrays = 1;		
	} else {
		// show nothing
	}
	
/*	
	// Set borders and fill parameters ------- Not finished yet. ---------------
	if (map.mapTypeId == 'roadmap') {
		var colorStroke 	= "#444444";
		var weightStroke	= 1.2;
		var opacityFill		= 0.1;
	} else if (map.mapTypeId == 'satellite') {
		var colorStroke 	= "#000000";
		var weightStroke	= 0.1;
		var opacityFill		= 0;
	} else if (map.mapTypeId == 'hybrid') {
		var colorStroke 	= "#ffffff";
		var weightStroke	= 1.2;
		var opacityFill		= 0;
	} else if (map.mapTypeId == 'terrain') {
		var colorStroke 	= "#ffffff";
		var weightStroke	= 1.2;
		var opacityFill		= 0;
	} 
	// -------------------------------------------------------------------------
*/	

    // If showing one country only (num_arrays == 1) ---------------------------
	// Calculate polygon
	if (num_arrays == 1 ) {   
		var geomAry1 = new Array(); 
		geomAry1 = returnGeom1.split('|'); 
		var XY1 = new Array(); 
		var points1 = []; 
		for (var i = 0; i < geomAry1.length; i++) { 
			XY1 = geomAry1[i].split(',');
			points1.push( new google.maps.LatLng(parseFloat(XY1[1]),parseFloat(XY1[0]))) ; 
		}		
    	// Construct the polygon
    	polygon1 = new google.maps.Polygon({
    	  paths: points1,    	      	  
    	  strokeColor: "#888888",
    	  strokeOpacity: 0.8,
    	  strokeWeight: 1,
    	  fillColor: "#ff0000",
    	  fillOpacity: 0.15
    	});
    	polygon1.setMap(map);
    }
    
    // If showing two countries at the same time (num_arrays == 2) --------------	
	if (num_arrays == 2) { 
		// Calculate polygon1
		var geomAry1 = new Array(); 
		geomAry1 = returnGeom1.split('|'); 
		var XY1 = new Array(); 
		var points1 = []; 
		for (var i = 0; i < geomAry1.length; i++) { 
			XY1 = geomAry1[i].split(',');
			points1.push( new google.maps.LatLng(parseFloat(XY1[1]),parseFloat(XY1[0]))) ; 
		} 
		
    	// Construct polygon1	
    	polygon1 = new google.maps.Polygon({
    	  paths: points1,
    	  strokeColor: "#888888",
    	  strokeOpacity: 0.8,
    	  strokeWeight: 1,
    	  fillColor: "#ff0000",
    	  fillOpacity: 0.15
    	});
    	polygon1.setMap(map);
    	
    	// Calculate polygon2
		var geomAry2 = new Array(); 
		geomAry2 = returnGeom2.split('|'); 
		var XY2 = new Array(); 
		var points2 = []; 
		for (var i = 0; i < geomAry2.length; i++) { 
			XY2 = geomAry2[i].split(',');
			points2.push( new google.maps.LatLng(parseFloat(XY2[1]),parseFloat(XY2[0]))) ; 
		}	
		
    	// Construct polygon2
    	polygon2 = new google.maps.Polygon({
    	  paths: points2,    	      	  
    	  strokeColor: "#888888",
    	  strokeOpacity: 0.8,
    	  strokeWeight: 1,
    	  fillColor: "#ff0000",
    	  fillOpacity: 0.15
    	});
    	polygon2.setMap(map);
	}

}


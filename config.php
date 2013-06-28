<?php

SYNC::$CONFIG['IGNORE_FILE_LIST'] = array(
	'.',
	'..',
	'.git*',
	'*.md',
	'*.markdown',
	'.htaccess',
	'Thumbs.db',
	'*.patch',
);



$foot = '</body></html>';

$filetype = array(
	'chm'     => 'application/octet-stream',
	'ppt'     => 'application/vnd.ms-powerpoint',
	'xls'     => 'application/vnd.ms-excel',
	'doc'     => 'application/msword',
	'exe'     => 'application/octet-stream',
	'rar'     => 'application/octet-stream',
	'js'      => "javascrīpt/js",
	'css'     => "text/css",
	'hqx'     => "application/mac-binhex40",
	'bin'     => "application/octet-stream",
	'oda'     => "application/oda",
	'pdf'     => "application/pdf",
	'ai'      => "application/postsrcipt",
	'eps'     => "application/postsrcipt",
	'es'      => "application/postsrcipt",
	'rtf'     => "application/rtf",
	'mif'     => "application/x-mif",
	'csh'     => "application/x-csh",
	'dvi'     => "application/x-dvi",
	'hdf'     => "application/x-hdf",
	'nc'      => "application/x-netcdf",
	'cdf'     => "application/x-netcdf",
	'latex'   => "application/x-latex",
	'ts'      => "application/x-troll-ts",
	'src'     => "application/x-wais-source",
	'zip'     => "application/zip",
	'bcpio'   => "application/x-bcpio",
	'cpio'    => "application/x-cpio",
	'gtar'    => "application/x-gtar",
	'shar'    => "application/x-shar",
	'sv4cpio' => "application/x-sv4cpio",
	'sv4crc'  => "application/x-sv4crc",
	'tar'     => "application/x-tar",
	'ustar'   => "application/x-ustar",
	'man'     => "application/x-troff-man",
	'sh'      => "application/x-sh",
	'tcl'     => "application/x-tcl",
	'tex'     => "application/x-tex",
	'texi'    => "application/x-texinfo",
	'texinfo' => "application/x-texinfo",
	't'       => "application/x-troff",
	'tr'      => "application/x-troff",
	'roff'    => "application/x-troff",
	'shar'    => "application/x-shar",
	'me'      => "application/x-troll-me",
	'ts'      => "application/x-troll-ts",
	'gif'     => "image/gif",
	'jpeg'    => "image/pjpeg",
	'jpg'     => "image/pjpeg",
	'jpe'     => "image/pjpeg",
	'ras'     => "image/x-cmu-raster",
	'pbm'     => "image/x-portable-bitmap",
	'ppm'     => "image/x-portable-pixmap",
	'xbm'     => "image/x-xbitmap",
	'xwd'     => "image/x-xwindowdump",
	'ief'     => "image/ief",
	'tif'     => "image/tiff",
	'tiff'    => "image/tiff",
	'pnm'     => "image/x-portable-anymap",
	'pgm'     => "image/x-portable-graymap",
	'rgb'     => "image/x-rgb",
	'xpm'     => "image/x-xpixmap",
	'txt'     => "text/plain",
	'c'       => "text/plain",
	'cc'      => "text/plain",
	'h'       => "text/plain",
	'html'    => "text/html",
	'htm'     => "text/html",
	'htl'     => "text/html",
	'rtx'     => "text/richtext",
	'etx'     => "text/x-setext",
	'tsv'     => "text/tab-separated-values",
	'mpeg'    => "video/mpeg",
	'mpg'     => "video/mpeg",
	'mpe'     => "video/mpeg",
	'avi'     => "video/x-msvideo",
	'qt'      => "video/quicktime",
	'mov'     => "video/quicktime",
	'moov'    => "video/quicktime",
	'movie'   => "video/x-sgi-movie",
	'au'      => "audio/basic",
	'snd'     => "audio/basic",
	'wav'     => "audio/x-wav",
	'aif'     => "audio/x-aiff",
	'aiff'    => "audio/x-aiff",
	'aifc'    => "audio/x-aiff",
	'swf'     => "application/x-shockwave-flash",
	'myz'     => "application/myz",
	"*"       => "application/octet-stream",
	"001"     => "application/x-001",
	"301"     => "application/x-301",
	"323"     => "text/h323",
	"906"     => "application/x-906",
	"907"     => "drawing/907",
	"a11"     => "application/x-a11",
	"acp"     => "audio/x-mei-aac",
	"ai"      => "application/postscript",
	"aif"     => "audio/aiff",
	"aifc"    => "audio/aiff",
	"aiff"    => "audio/aiff",
	"anv"     => "application/x-anv",
	"asa"     => "text/asa",
	"asf"     => "video/x-ms-asf",
	"asp"     => "text/asp",
	"asx"     => "video/x-ms-asf",
	"au"      => "audio/basic",
	"avi"     => "video/avi",
	"awf"     => "application/vnd.adobe.workflow",
	"biz"     => "text/xml",
	"bmp"     => "application/x-bmp",
	"bot"     => "application/x-bot",
	"c4t"     => "application/x-c4t",
	"c90"     => "application/x-c90",
	"cal"     => "application/x-cals",
	"cat"     => "application/vnd.ms-pki.seccat",
	"cdf"     => "application/x-netcdf",
	"cdr"     => "application/x-cdr",
	"cel"     => "application/x-cel",
	"cer"     => "application/x-x509-ca-cert",
	"cg4"     => "application/x-g4",
	"cgm"     => "application/x-cgm",
	"cit"     => "application/x-cit",
	"class"   => "java/*",
	"cml"     => "text/xml",
	"cmp"     => "application/x-cmp",
	"cmx"     => "application/x-cmx",
	"cot"     => "application/x-cot",
	"crl"     => "application/pkix-crl",
	"crt"     => "application/x-x509-ca-cert",
	"csi"     => "application/x-csi",
	"css"     => "text/css",
	"cut"     => "application/x-cut",
	"dbf"     => "application/x-dbf",
	"dbm"     => "application/x-dbm",
	"dbx"     => "application/x-dbx",
	"dcd"     => "text/xml",
	"dcx"     => "application/x-dcx",
	"der"     => "application/x-x509-ca-cert",
	"dgn"     => "application/x-dgn",
	"dib"     => "application/x-dib",
	"dll"     => "application/x-msdownload",
	"doc"     => "application/msword",
	"dot"     => "application/msword",
	"drw"     => "application/x-drw",
	"dtd"     => "text/xml",
	"dwf"     => "Model/vnd.dwf",
	"dwf"     => "application/x-dwf",
	"dwg"     => "application/x-dwg",
	"dxb"     => "application/x-dxb",
	"dxf"     => "application/x-dxf",
	"edn"     => "application/vnd.adobe.edn",
	"emf"     => "application/x-emf",
	"eml"     => "message/rfc822",
	"ent"     => "text/xml",
	"epi"     => "application/x-epi",
	"eps"     => "application/x-ps",
	"eps"     => "application/postscript",
	"etd"     => "application/x-ebx",
	"exe"     => "application/x-msdownload",
	"fax"     => "image/fax",
	"fdf"     => "application/vnd.fdf",
	"fif"     => "application/fractals",
	"fo"      => "text/xml",
	"frm"     => "application/x-frm",
	"g4"      => "application/x-g4",
	"gbr"     => "application/x-gbr",
	"gcd"     => "application/x-gcd",
	"gif"     => "image/gif",
	"gl2"     => "application/x-gl2",
	"gp4"     => "application/x-gp4",
	"hgl"     => "application/x-hgl",
	"hmr"     => "application/x-hmr",
	"hpg"     => "application/x-hpgl",
	"hpl"     => "application/x-hpl",
	"hqx"     => "application/mac-binhex40",
	"hrf"     => "application/x-hrf",
	"hta"     => "application/hta",
	"htc"     => "text/x-component",
	"htm"     => "text/html",
	"html"    => "text/html",
	"htt"     => "text/webviewhtml",
	"htx"     => "text/html",
	"icb"     => "application/x-icb",
	"ico"     => "image/x-icon",
	"ico"     => "application/x-ico",
	"iff"     => "application/x-iff",
	"ig4"     => "application/x-g4",
	"igs"     => "application/x-igs",
	"iii"     => "application/x-iphone",
	"img"     => "application/x-img",
	"ins"     => "application/x-internet-signup",
	"isp"     => "application/x-internet-signup",
	"IVF"     => "video/x-ivf",
	"java"    => "java/*",
	"jfif"    => "image/jpeg",
	"jpe"     => "image/jpeg",
	"jpe"     => "application/x-jpe",
	"jpeg"    => "image/jpeg",
	"jpg"     => "image/jpeg",
	"jpg"     => "application/x-jpg",
	"js"      => "application/x-javascript",
	"jsp"     => "text/html",
	"la1"     => "audio/x-liquid-file",
	"lar"     => "application/x-laplayer-reg",
	"latex"   => "application/x-latex",
	"lavs"    => "audio/x-liquid-secure",
	"lbm"     => "application/x-lbm",
	"lmsff"   => "audio/x-la-lms",
	"ls"      => "application/x-javascript",
	"ltr"     => "application/x-ltr",
	"m1v"     => "video/x-mpeg",
	"m2v"     => "video/x-mpeg",
	"m3u"     => "audio/mpegurl",
	"m4e"     => "video/mpeg4",
	"mac"     => "application/x-mac",
	"man"     => "application/x-troff-man",
	"math"    => "text/xml",
	"mdb"     => "application/msaccess",
	"mdb"     => "application/x-mdb",
	"mfp"     => "application/x-shockwave-flash",
	"mht"     => "message/rfc822",
	"mhtml"   => "message/rfc822",
	"mi"      => "application/x-mi",
	"mid"     => "audio/mid",
	"midi"    => "audio/mid",
	"mil"     => "application/x-mil",
	"mml"     => "text/xml",
	"mnd"     => "audio/x-musicnet-download",
	"mns"     => "audio/x-musicnet-stream",
	"mocha"   => "application/x-javascript",
	"movie"   => "video/x-sgi-movie",
	"mp1"     => "audio/mp1",
	"mp2"     => "audio/mp2",
	"mp2v"    => "video/mpeg",
	"mp3"     => "audio/mp3",
	"mp4"     => "video/mpeg4",
	"mpa"     => "video/x-mpg",
	"mpd"     => "application/vnd.ms-project",
	"mpe"     => "video/x-mpeg",
	"mpeg"    => "video/mpg",
	"mpg"     => "video/mpg",
	"mpga"    => "audio/rn-mpeg",
	"mpp"     => "application/vnd.ms-project",
	"mps"     => "video/x-mpeg",
	"mpt"     => "application/vnd.ms-project",
	"mpv"     => "video/mpg",
	"mpv2"    => "video/mpeg",
	"mpw"     => "application/vnd.ms-project",
	"mpx"     => "application/vnd.ms-project",
	"mtx"     => "text/xml",
	"mxp"     => "application/x-mmxp",
	"net"     => "image/pnetvue",
	"nrf"     => "application/x-nrf",
	"nws"     => "message/rfc822",
	"odc"     => "text/x-ms-odc",
	"out"     => "application/x-out",
	"p10"     => "application/pkcs10",
	"p12"     => "application/x-pkcs12",
	"p7b"     => "application/x-pkcs7-certificates",
	"p7c"     => "application/pkcs7-mime",
	"p7m"     => "application/pkcs7-mime",
	"p7r"     => "application/x-pkcs7-certreqresp",
	"p7s"     => "application/pkcs7-signature",
	"pc5"     => "application/x-pc5",
	"pci"     => "application/x-pci",
	"pcl"     => "application/x-pcl",
	"pcx"     => "application/x-pcx",
	"pdf"     => "application/pdf",
	"pdf"     => "application/pdf",
	"pdx"     => "application/vnd.adobe.pdx",
	"pfx"     => "application/x-pkcs12",
	"pgl"     => "application/x-pgl",
	"pic"     => "application/x-pic",
	"pko"     => "application/vnd.ms-pki.pko",
	"pl"      => "application/x-perl",
	"plg"     => "text/html",
	"pls"     => "audio/scpls",
	"plt"     => "application/x-plt",
	"png"     => "image/png",
	"png"     => "application/x-png",
	"pot"     => "application/vnd.ms-powerpoint",
	"ppa"     => "application/vnd.ms-powerpoint",
	"ppm"     => "application/x-ppm",
	"pps"     => "application/vnd.ms-powerpoint",
	"ppt"     => "application/vnd.ms-powerpoint",
	"ppt"     => "application/x-ppt",
	"pr"      => "application/x-pr",
	"prf"     => "application/pics-rules",
	"prn"     => "application/x-prn",
	"prt"     => "application/x-prt",
	"ps"      => "application/x-ps",
	"ps"      => "application/postscript",
	"ptn"     => "application/x-ptn",
	"pwz"     => "application/vnd.ms-powerpoint",
	"r3t"     => "text/vnd.rn-realtext3d",
	"ra"      => "audio/vnd.rn-realaudio",
	"ram"     => "audio/x-pn-realaudio",
	"ras"     => "application/x-ras",
	"rat"     => "application/rat-file",
	"rdf"     => "text/xml",
	"rec"     => "application/vnd.rn-recording",
	"red"     => "application/x-red",
	"rgb"     => "application/x-rgb",
	"rjs"     => "application/vnd.rn-realsystem-rjs",
	"rjt"     => "application/vnd.rn-realsystem-rjt",
	"rlc"     => "application/x-rlc",
	"rle"     => "application/x-rle",
	"rm"      => "application/vnd.rn-realmedia",
	"rmf"     => "application/vnd.adobe.rmf",
	"rmi"     => "audio/mid",
	"rmj"     => "application/vnd.rn-realsystem-rmj",
	"rmm"     => "audio/x-pn-realaudio",
	"rmp"     => "application/vnd.rn-rn_music_package",
	"rms"     => "application/vnd.rn-realmedia-secure",
	"rmvb"    => "application/vnd.rn-realmedia-vbr",
	"rmx"     => "application/vnd.rn-realsystem-rmx",
	"rnx"     => "application/vnd.rn-realplayer",
	"rp"      => "image/vnd.rn-realpix",
	"rpm"     => "audio/x-pn-realaudio-plugin",
	"rsml"    => "application/vnd.rn-rsml",
	"rt"      => "text/vnd.rn-realtext",
	"rtf"     => "application/msword",
	"rtf"     => "application/x-rtf",
	"rv"      => "video/vnd.rn-realvideo",
	"sam"     => "application/x-sam",
	"sat"     => "application/x-sat",
	"sdp"     => "application/sdp",
	"sdw"     => "application/x-sdw",
	"sit"     => "application/x-stuffit",
	"slb"     => "application/x-slb",
	"sld"     => "application/x-sld",
	"slk"     => "drawing/x-slk",
	"smi"     => "application/smil",
	"smil"    => "application/smil",
	"smk"     => "application/x-smk",
	"snd"     => "audio/basic",
	"sol"     => "text/plain",
	"sor"     => "text/plain",
	"spc"     => "application/x-pkcs7-certificates",
	"spl"     => "application/futuresplash",
	"spp"     => "text/xml",
	"ssm"     => "application/streamingmedia",
	"sst"     => "application/vnd.ms-pki.certstore",
	"stl"     => "application/vnd.ms-pki.stl",
	"stm"     => "text/html",
	"sty"     => "application/x-sty",
	"svg"     => "text/xml",
	"swf"     => "application/x-shockwave-flash",
	"tdf"     => "application/x-tdf",
	"tg4"     => "application/x-tg4",
	"tga"     => "application/x-tga",
	"tif"     => "image/tiff",
	"tif"     => "application/x-tif",
	"tiff"    => "image/tiff",
	"tld"     => "text/xml",
	"top"     => "drawing/x-top",
	"torrent" => "application/x-bittorrent",
	"tsd"     => "text/xml",
	"txt"     => "text/plain",
	"uin"     => "application/x-icq",
	"uls"     => "text/iuls",
	"vcf"     => "text/x-vcard",
	"vda"     => "application/x-vda",
	"vdx"     => "application/vnd.visio",
	"vml"     => "text/xml",
	"vpg"     => "application/x-vpeg005",
	"vsd"     => "application/vnd.visio",
	"vsd"     => "application/x-vsd",
	"vss"     => "application/vnd.visio",
	"vst"     => "application/vnd.visio",
	"vst"     => "application/x-vst",
	"vsw"     => "application/vnd.visio",
	"vsx"     => "application/vnd.visio",
	"vtx"     => "application/vnd.visio",
	"vxml"    => "text/xml",
	"wav"     => "audio/wav",
	"wax"     => "audio/x-ms-wax",
	"wb1"     => "application/x-wb1",
	"wb2"     => "application/x-wb2",
	"wb3"     => "application/x-wb3",
	"wbmp"    => "image/vnd.wap.wbmp",
	"wiz"     => "application/msword",
	"wk3"     => "application/x-wk3",
	"wk4"     => "application/x-wk4",
	"wkq"     => "application/x-wkq",
	"wks"     => "application/x-wks",
	"wm"      => "video/x-ms-wm",
	"wma"     => "audio/x-ms-wma",
	"wmd"     => "application/x-ms-wmd",
	"wmf"     => "application/x-wmf",
	"wml"     => "text/vnd.wap.wml",
	"wmv"     => "video/x-ms-wmv",
	"wmx"     => "video/x-ms-wmx",
	"wmz"     => "application/x-ms-wmz",
	"wp6"     => "application/x-wp6",
	"wpd"     => "application/x-wpd",
	"wpg"     => "application/x-wpg",
	"wpl"     => "application/vnd.ms-wpl",
	"wq1"     => "application/x-wq1",
	"wr1"     => "application/x-wr1",
	"wri"     => "application/x-wri",
	"wrk"     => "application/x-wrk",
	"ws"      => "application/x-ws",
	"ws2"     => "application/x-ws",
	"wsc"     => "text/scriptlet",
	"wsdl"    => "text/xml",
	"wvx"     => "video/x-ms-wvx",
	"xdp"     => "application/vnd.adobe.xdp",
	"xdr"     => "text/xml",
	"xfd"     => "application/vnd.adobe.xfd",
	"xfdf"    => "application/vnd.adobe.xfdf",
	"xhtml"   => "text/html",
	"xls"     => "application/vnd.ms-excel",
	"xls"     => "application/x-xls",
	"xlw"     => "application/x-xlw",
	"xml"     => "text/xml",
	"xpl"     => "audio/scpls",
	"xq"      => "text/xml",
	"xql"     => "text/xml",
	"xquery"  => "text/xml",
	"xsd"     => "text/xml",
	"xsl"     => "text/xml",
	"xslt"    => "text/xml",
	"xwd"     => "application/x-xwd",
	"x_b"     => "application/x-x_b",
	"x_t"     => "application/x-x_t"
);




?>
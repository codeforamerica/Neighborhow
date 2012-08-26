/**
/*
Theme Name:     Neighborhow
Theme URI:      http://neighborhow.org/
Description:	My theme 
Author:         E Hunt
Author URI:     http://neighborhow.org/

Version:        0.1.0
*/

/*--------COLORS ---------
	01779F = blue
	BEF202 = neon green
	DE4526 = red
	E5D599 = beige
	51B4D4 = light blue for hover
	3498A7 = pool blue
	958032 = darker beige for current item
	
	CDD980 = pea green
	C3E3ED = bar blue
	F19435 = orange
	5490AE = font blue
-------------------------*/


/* WordPress classes 
-------------------------------------------------------------- */
.hfeed h1, .hfeed h2, .hfeed h3, .hfeed h4, .hfeed h5, .hfeed h6 { font-weight: normal; }
img.wp-smiley {
	max-height: 0.8125em;
	margin: 0;
	padding: 0;
	border: none;
}
.gallery {
	display: block;
	text-align: center;
	margin-bottom: 1.692307em !important;
}
.left, .alignleft {
	float: left;
	margin: 0 15px 5px 0;
}
.right, .alignright {
	float: right;
	margin: 0 0 10px 25px;
}
.center, .aligncenter {
	display: block;
	margin: 0 auto 1.692307em auto !important;
}
.block, .alignnone {
	display: block;
	margin: 0 0 1.692307em 0;
}
img.alignleft, img.alignright {
	margin-top: 5px;
	display: inline;
}
blockquote.alignleft, blockquote .alignright { width: 33%; }
.byline abbr, .entry-meta abbr, .comment-meta abbr { border: none; }
.clear { clear: both; }

/* Tables 
-------------------------------------------------------------- */
table {
	margin: 0 0 1.692307em 0;
	width: 100%;
}
table caption {
	font-size: 0.8125em;
	line-height: 1.692307em;
	color: #888;
}
table th {
	font-size: 0.8461538461538462em;
	line-height: 1.692307em;
	font-weight: normal;
	text-transform: uppercase;
	padding: 10px 2%;
	border-bottom: none;
	border-bottom: 3px solid #e7e7e7;
	text-align: left;
}
td {
	padding: 0.8125em 2%;
	color: #888;
	border-bottom: 1px solid #e7e7e7;
}

/* Lists 
-------------------------------------------------------------- */
ul li, ol li { line-height: 2.1em; }
dl { margin: 0 0 20px 30px; }
dl dt {
	margin: 0;
	font-size: 1.230769230769231em;		/* 16 / 13 = 1.230769230769231 */
	line-height: 1.692307em;
/*	font-family: Georgia, 'Times New Roman', Times, serif;*/
	font-style: italic;
	font-weight: normal;
}
dl dd {
	margin: 0 0 5px 20px;
	padding: 0;
	color: #888;
}

/* Blockquotes
-------------------------------------------------------------- */
blockquote, blockquote blockquote blockquote {
	overflow: hidden;
	padding: 0 0 0 40px;
	font-size: 1.153846153846154em;		/* 15 / 13 = 1.153846153846154 */
/*	font-family: Georgia, 'Times New Roman', Times, serif;*/
	font-style: italic;
	color: #aaa;
	background: url(images/quote.png) no-repeat 0 4px;
}

/* Code 
-------------------------------------------------------------- */
code {
	padding: 0 3px;
	color: #555;
	background: #ffeacd;
}
pre {
	padding: 15px 20px;
	background: #fff1dd;
	border: 1px solid #f6e4cc;
}
pre code {
	padding: 0;
	background: transparent;
}

/* Forms 
-------------------------------------------------------------- */
form label, form input, form textarea {
/*	font-family: 'Bitter', Georgia, 'Times New Roman', Times, serif;*/
}
form label {
	line-height: 1.5em;	
	color: #222;
}
input[type="text"], input[type="password"], input[type="email"], .input-text, textarea, select {
	border: 1px solid #ddd;
	padding: 5px;
	outline: none;
	font-size: 0.8125em;
	color: #888;
	margin: 0;
	display: block;
	background: #fff;
}
select { padding: 0; }
input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, textarea:focus, .input-text:focus {
	border: 1px solid #aaa;
	color: #444;
	-moz-box-shadow: 0 0 3px rgba(0,0,0,.2);
	-webkit-box-shadow: 0 0 3px rgba(0,0,0,.2);
	box-shadow:  0 0 3px rgba(0,0,0,.2);
}
textarea {
	display: block;
	width: 94%;
	min-height: 60px;
}
input[type="radio"] { vertical-align: text-middle; }
input[type="checkbox"] { display: inline; }
input[type="submit"]:hover { cursor: pointer }
.error { color: #ff4367; }

/* Images & Video
-------------------------------------------------------------- */
a:hover img { opacity: 1; }
#site-title a:hover img { border: none; }
.hentry img, .entry-content img, .widget img, .wp-caption, .hentry embed, .entry-content embed, .widget embed, .hentry object, .entry-content object, .widget object, .hentry video, .entry-content video, .widget video {
	max-width: 100%;
}
.hentry img, .entry-content img, .widget img, .wp-caption {
	height: auto;
	padding: 2px;
	border: 1px solid #ddd;	
}

/* Captions [caption] 
-------------------------------------------------------------- */
.wp-caption {
	overflow: hidden;
	text-align: center;
}
.wp-caption img { margin: 0 0 5px 0; }
.wp-caption .wp-caption-text {
	margin: 5px 0;
	font-size: 0.8461538461538462em;
	line-height: 1em;
	color: #888;
	text-align: left;
}
.wp-caption a { border: none; }

/* Galleries 
-------------------------------------------------------------- */
.gallery {
	display: block;
	clear: both;
	overflow: hidden;
	margin: 0 auto;
	margin: 0 !important;
}
.gallery br {
	display: block;
	line-height: 0;
	height: 0;
}
.gallery a { border: none; }
.gallery .gallery-row {
	display: block;
	clear: both;
	overflow: hidden;
	margin: 0
}
.gallery .gallery-item {
	overflow: hidden;
	float: left;
	margin: 0;
	margin: 0 0 1.692307em 0 !important;
	text-align: left;
	list-style: none;
	padding: 0;
}
.gallery img, .gallery .gallery-item .gallery-icon img {
	max-width: 89%;
	height: auto;
	margin: 0 auto
}
.gallery-icon {
	overflow: hidden;
	margin: 0;
}
.gallery-caption {
	margin: 0;
	font-size: 0.8461538461538462em;
	line-height: 1.4em;
	color: #aaa;
}
.attachment-image {
	float: left;
	width: 100%;
}
.singular-attachment .hentry .gallery-caption { display: none; /* Hide captions in gallery on attachment pages */ }
.gallery-caption { margin: 3px 0 0 0 }
.col-0 { width: 100% }
.col-1 { width: 100% }
.col-2 { width: 50% }
.col-3 { width: 33.33% }
.col-4 { width: 25% }
.col-5 { width: 20% }
.col-6 { width: 16.66% }
.col-7 { width: 14.28% }
.col-8 { width: 12.5% }
.col-9 { width: 11.11% }
.col-10 { width: 10% }
.col-11 { width: 9.09% }
.col-12 { width: 8.33% }
.col-13 { width: 7.69% }
.col-14 { width: 7.14% }
.col-15 { width: 6.66% }
.col-16 { width: 6.25% }
.col-17 { width: 5.88% }
.col-18 { width: 5.55% }
.col-19 { width: 5.26% }
.col-20 { width: 5% }
.col-21 { width: 4.76% }
.col-22 { width: 4.54% }
.col-23 { width: 4.34% }
.col-24 { width: 4.16% }
.col-25 { width: 4% }
.col-26 { width: 3.84% }
.col-27 { width: 3.7% }
.col-28 { width: 3.57% }
.col-29 { width: 3.44% }
.col-30 { width: 3.33% }


/*------------ BASICS --------------------*/
body {
	font-family:"Open Sans","Helvetica Neue","HelveticaNeue",Helvetica,Arial,sans-serif !important;
	font-size:14px !important;
	line-height:140% !important;
	color:#555;
	background:#fff;
	min-height:800px !important;
}
a {
	color: #01779F;
	text-decoration: none;
}
a:hover {
	text-decoration: none;
	color: #F19435;
/*	border-bottom:1px solid #F19435;
	padding-bottom:.15em;*/
}
a img { 
	opacity: 1; 
	filter: alpha(opacity=100);	
	text-decoration:none;
	border-bottom:none;
}
ul {
	list-style-position:outside;
	list-style:none;
	margin:0;
	padding:0;
}

/*------------ LAYOUT --------------------*/
.row-header {
	background:#fff;
	margin:1em auto 1em auto;
	padding:0;
}
.row-nav,
.row-footer {
	margin:0 auto 0 auto;
	padding:0 !important;
	background:#01779F;
	border-top:4px solid #CDD980;
	border-bottom:4px solid #CDD980;
}
.row-promo {
	margin:0 auto 0 auto;	
	background:#fff; /*DAF1F9; /*C3E3ED;*/
	border-bottom:4px solid #CDD980;	
}
.row-content {
	background:#fff;
}
#main {
	clear: both;
	width: 100%;
	margin: 0 auto;
	position: relative;
}
.page-template-fullwidth #content { width: 100%; }
#content {
	float: left;
	width: 68.08510638297872%;
	margin: 0 0 30px 0;
	min-height: 180px;
}
#sidebar-primary {
	float: right;
	width: 27.65957446808511%;
}
.wrapper {
	max-width: 940px;
	margin: 0 auto;
	position: relative;
}
#comments-template { clear: left; }
#sidebar-subsidiary {
	overflow: visible;
	width: 100%;
	margin: 30px auto 0 auto;
	padding: 30px 0 0 0;
	border-top: 5px solid #444;
	clear: both;
}
#footer {
	overflow: hidden;
	clear: both;
	width: 100%;
	margin: 0 auto;
	padding: 26px 0 30px 0;
	border-top: 5px solid #444;
	font-size: 0.8461538461538462em;
	color: #aaa;
}

/*------------ HEADER --------------------*/
#banner {
	padding:0 1em 0 1em;
	margin:0 0 0 0;
	position:relative;
}
#brand {
	float: left;
	width: 100%;
	overflow:visible;
	margin:0 0 0 0;
	padding:0;	
}
.site-title {
	float:left;
	font-size: 1.45em;
	line-height: 1em;
	font-weight: normal;
	margin:1.25em 0 0 0;
	letter-spacing: .075em;
}
#site-title img {
	float:left;
	height:70px; 
	margin:0 .6em 0em 0;
}
#header img:hover,
a.home-brand:hover {
	text-decoration:none;
	border-bottom:none;
}
#menu-header {
	float-right;
	margin:0;
	padding:0 1.5em 0 0;
}
ul.header-elements {
	float:right;	
}
li.header-element {
	float:right;
	padding-left:1.5em;	
}
#menu-header li.header-search.current-item a > #searchform input{ 
	background:#eee;
}
/*--------------------- Header Search ----------*/
#searchform {
	margin:1.4em 0 0 0;
	height:19px;
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
}
#searchform input#s {
	background: url(images/icons/search.png) no-repeat 14.25em .35em;
	width:15em;
	height:19px;
	border-color:#ddd;	
	background-color:#fff;
	font-size:.85em;	
	font-family:"Open Sans","Helvetica Neue","HelveticaNeue",Helvetica,Arial,sans-serif;	
}
#searchform #searchsubmit {
	display: none;
}

/*--------------------- NAV --------------------*/
#nhnavigation {
	clear: both;
	padding:0 0 0 .9em;
	margin:0 auto 0 auto;
	position:relative;
}
ul.nhnav-items { 
}
li.nhnav-item {
	display:block;
	float:left;
	margin:0 2em 0 0;
	min-height:20px;
	font-size:.95em;
	font-weight:600;
}
li.nhnav-item a {
	display:block;
	padding: .4em 1.5em .3em 1em !important;
	color:#fff;	
}	
li.nhnav-item a:hover {
	background:#F19435;
	color:#fff;
}	
li.nhnav-avatar { }
li.nhnav-avatar a,
li.nhnav-avatar a:hover {
/*	padding: .75em 1.5em .4em 1.5em;*/
}
li.nhnav-avatar img {
	border:1px solid #ddd;
	margin:-2px 3px 0 0;
	padding:0;
}
li.nhnav-item.current-item a { 
	color: #fff;
	background:#CDD980;
}
/*---------------------Nav Dropdown-------------*/	
.dropdown .dropdown-toggle .caret{
	margin-top:11px;
	margin-left:2px;
	border-top-color: #fff;
	border-bottom-color: #fff;
	opacity: 1;
	filter: alpha(opacity=100);
}
.dropdown-toggle{
	margin-top:0em;
}	
ul.dropdown-menu {
	background:#eee;
}	
ul.dropdown-menu {
	background-color: #eee;
}
.dropdown { }
li.sub-menu {
	clear: both;
	float: none;
	padding:.25em 0 .25em 0;
	border-top: 1px dotted #01779F;
	width:100%;	
}
li.sub-menu a {
	color:#555;
	width:100%;
}
li.sub-menu a:hover {
	background: #F19435;
	color:#fff;
	width:auto;
}
li.sub-menu:first-child, 
li.sub-menu.current-item li:first-child { 
	border-top: none; 
}
li.current-item > ul > li.sub-menu > a {
	width:auto;
	color:#555;
	background:transparent;
}
li.current-item > ul > li.sub-menu > a:hover {
	background:#F19435;
	color:#fff;
	width:auto;
}

/*--------------------- PROMO --------------------*/
#site-promo {
	clear: both;
	height:90px !important;
	padding:0 0 0 0;	
	margin:0 auto 0 auto;
	position: relative;
}
#site-promo h2 {
	font-size: 1.75em;
	float: left;
	width:66%;
	margin:.3em 0 0 1.5em;	
	padding:0;
	line-height: 130%;
	color:#555;
	font-weight:normal;
	text-align: center;
	font-family:"Droid Serif",Georgia,"Times New Roman",Times,serif;

}
p.buttons {
	float:left;
	padding:0;	
	margin:-.3em 0 0 2em;
	line-height:3em;
	font-size:1em;
	letter-spacing:.05em;
}



/*---------------------BUTTONS---------------------*/
.nh-btn-orange,
.nh-btn-blue {
	padding: 5px 12px 6px 12px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}
.nh-btn-orange {
	background:#F19435;
	color:#fff;
}
.nh-btn-blue {
	background:#01779F;
	color:#fff;
}
.nh-btn-orange:hover,
.nh-btn-blue:hover {
	background:#444;
	color:#fff;
}








.breadcrumbs {
	font-size: 0.8461538461538462em;
	color: #888;
	float: left;
	width: 97.872340425532%;
	border-top: 1px solid #e5e5e5;
	border-bottom: 1px solid #e5e5e5;
	margin: 0 0 30px 0;
}
.breadcrumb-trail { padding: 1px 1.086956521739%; }

/* Posts 
-------------------------------------------------------------- */
.hentry {
	float: left;
	width: 100%;
	margin: 0 0 20px 0;
	padding: 0 0 5px 0;
	font-size: 1em;
	border-bottom: 1px solid #e5e5e5;
}
.singular .hentry {
	margin: 0 0 30px 0;
	position: relative;
	float: left;
	width: 100%;
}
.featured { margin-bottom: 20px; }
.sticky .sticky-header {
	float: left;
	width: 30%;
}
.sticky .byline { clear: left }
.sticky .entry-summary {
	float: right;
	width: 65%;
	border-left: 1px solid #e5e5e5;
	padding-left: 2.34375%;
}
.hentry .featured-thumbnail { margin-bottom: 25px; }
.hentry .thumbnail {
	float: left;
	width: 23.4375%;
	margin: 3px 3.90625% 20px 0;
}

/* Post titles 
-------------------------------------------------------------- */
.hentry .entry-title {
	margin: 0 0 0.6em 0;
	padding: 0;
	font-size: 1.230769230769231em;	/* 16 / 13 = 1.230769230769231 */
	text-transform: uppercase;
	line-height: 1.3em;
	border: none;
	color: #333;
	word-spacing: 2px;
}
.singular .entry-title {
	font-size: 1.846153846153846em;	/* 24 / 13 = 1.846153846153846 */
	margin-bottom: 0.625em;
}
.singular-page .entry-title { color: #ccc; }
.singular-page .entry-title, .singular-attachment .entry-title { margin-bottom: 1.2em; }
.entry-title a, .entry-title a:visited { color: #222; }
.entry-title a:hover { color: #dd5424; }

/* Post bylines/datelines 
-------------------------------------------------------------- */
.byline {
	font-family: Georgia, 'Times New Roman', Times, serif;
	font-style: italic;
	margin: 0 0 1em 0;
	font-size: 0.8461538461538462em;
	color: #aaa;
	line-height: 1.692307em;
	word-spacing: 2px;
}
.singular .byline { margin-bottom: 1.7em; }
.byline a, .byline a:visited { color: #aaa; }
.byline a:hover { color: #000; }
.author, .published, .category, .edit {
	font-family: 'Bitter', Georgia, 'Times New Roman', Times, serif;
	font-style: normal;
}
.comment-list .published, .comment-list .edit, .comment-list .comment-reply-link {
	font-family: Georgia, 'Times New Roman', Times, serif;
	font-size: 1em;
	font-style: italic;
	text-transform: none;
}

/* Post excerpts 
-------------------------------------------------------------- */
.entry-summary p { margin: 0 0 1em 0; }

/* Post metadata 
-------------------------------------------------------------- */
.entry-meta {
	margin: 0 0 25px 0;
	font-size: 0.8461538461538462em;
	color: #aaa;
}
.entry-meta a { color: #888; }
.entry-meta a:hover { color: #000; }

/* Singular post prev/next links 
-------------------------------------------------------------- */
.singular .loop-nav {
	font-size: 0.8461538461538462em;
	color: #888;
	clear: left;
}

/* Page links for multi-paged posts <!--nextpage--> 
-------------------------------------------------------------- */
.page-links {
	clear: both;
	font-size: 0.8461538461538462em;
	word-spacing: 2px;
	line-height: 1em;
	color: #222;
}
.entry-summary .page-links {
	clear: none;
	font-size: 0.8461538461538462em;
	line-height: 1em;
	color: #aaa;
}
.page-links a, .page-links a:visited {
	display: inline-block;
	color: #555;
	background: #e9e9e9;
	padding: 3px 6px;
}
.page-links a:hover {
	color: #fff;
	background: #555;
}

/* Archive/search pagination and comment pagination 
-------------------------------------------------------------- */
.comment-navigation { margin-bottom: 1.692307em; }
.pagination.loop-pagination {
	float: left;
	clear: both;
	margin-top: 7px;
}
.pagination .page-numbers, .comment-navigation .page-numbers {
	display: inline-block;
	padding: 4px 8px;
	margin: 0;
	line-height: 1em;
	color: #444;
}
.pagination a.page-numbers, .comment-navigation a.page-numbers {
	color: #333;
	background: #e9e9e9;
}
.pagination a:hover, .comment-navigation a:hover {
	color: #fff;
	background: #555;
}
.pagination .current, .comment-navigation .current { color: #aaa; }

/* Sidebar after singular posts 
-------------------------------------------------------------- */
#sidebar-after-singular {
	overflow: hidden;
	margin: 0;
	font-size: 1em;
	float: left;
}

/* Widgets 
-------------------------------------------------------------- */
.sidebar .widget {
	float: left;
	width: 100%;
	margin-bottom: 26px;
	color: #888;
}
.widget table, .widget ul, .widget ol { margin-bottom: 0; }

/* Widget titles 
-------------------------------------------------------------- */
.sidebar .widget-title {
	font-size: 0.7692307692307692em;	/* 10 / 13 = 0.7692307692307692 */
	color: #aaa;
	text-transform: uppercase;
	letter-spacing: 1px;
	word-spacing: 2px;
}

/* Widget unordered lists 
-------------------------------------------------------------- */
.sidebar .widget ul { margin-left: 14px; }
.sidebar .widget ul li a { color: #555; }
.sidebar .widget ul li a:hover { color: #000; }
.sidebar .widget ul li a { color: #555; }
.sidebar .widget ul li a:hover { color: #000; }

/* Search form widget
-------------------------------------------------------------- */
.widget .search-form { overflow: hidden; }
.widget .search-form label {
	font-size: 0.8461538461538462em;
	line-height: 1.692307em;
	color: #aaa;
}
.widget .search-form input[type="text"] {
	width: 91.538461%;
	float: left;
	padding: 8px 10px;
	font-size: 1em;
	color: #aaa;
}
.search-form .search-submit, .widget.search .widget-title { display: none; }

/* Comments 
-------------------------------------------------------------- */
.comment-list, .comment-list ol {
	list-style: none;
	margin: 0 0 25px 0;
}
.comment-list { margin-bottom: 40px; }
.comment-list li {
	padding: 15px 0 0 0;
	border-top: none;
}
.comment-list li li { padding-left: 10.15625%; }	/* 65px / 640px = 10.15625% */
.comment-list .avatar {
	float: left;
	margin: 0 15px 10px 0;
}
.comment-meta {
	margin: 0 0 10px 0;
	font-size: 0.8461538461538462em;
	color: #aaa;
	line-height: 1.692307em;
}
.comment-meta .comment-author cite {
	font-style: normal;
	font-weight: bold;
	color: #333;
	font-size: 1.153846153846154em;
}
.comment-author { margin-right: 10px; }
.comment-meta a {
	font-style: normal;
	color: #aaa;
}
.comment-meta a:hover { color: #000; }
.comments-closed {
	padding: 10px 25px;
	font-size: 1em;
	color: #888;
	background: #f5f5f5;
}
.comment-content { margin-left: 65px; }
.comment-content p { margin-bottom: 1em; }

/* Comment form 
-------------------------------------------------------------- */
#respond {
	overflow: hidden;
	width: 75%;
}
.comment-list #respond { margin-top: 1.692307em; }
#respond .log-in-out {
	font-size: 0.8461538461538462em;
	line-height: 1em;
	color: #888;
	margin-bottom: -7px;
}
#reply-title small a {
	font-size: 0.8461538461538462em;
	line-height: 1.692307em;
	background: #fff;
	padding-right: 8px;
}
#respond label {
	font-size: 0.8461538461538462em;
	line-height: 1.692307em;
	color: #bbb;
}
#respond p.req label {
	color: #888;
	line-height: 2.4em;
}
#respond span.required {
	font-size: 1em;
	font-weight: bold;
	color: #000;
}
#respond #submit { margin-bottom: 1.692307em; }
#commentform input {
	display: inline;
	margin-right: 10px;
}
#commentform p {
	margin-bottom: 15px;
	line-height: 1em;
}
#comment { margin-top: 20px; }

/* Subsidiary sidebar 
-------------------------------------------------------------- */
#sidebar-subsidiary .widget {
	float: left;
	width: 21.80851063829787%;	/* 205px / 940px = 21.80851063829787% */
	margin: 0 3.191489361702128% 20px 0;	/* 30px / 940px = 3.191489361702128% */
	font-size: 1em;
}

/* Footer 
-------------------------------------------------------------- */
#footer p { margin-bottom: 0; }
#footer a { color: #888; }
#footer a:hover { color: #dd5424; }

/*  Buttons (submit etc.)
-------------------------------------------------------------- */
#respond #submit, .button, a.button, .wpcf7-submit, #loginform .button-primary {
	padding: 5px 12px 6px 12px;
	color: #fff;
	background:#db5629;
	border: none;
	height: 33px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;
}
#respond #submit:hover, .button:hover, .wpcf7-submit:hover, #loginform .button-primary:hover { background: #222; }
.menu li a { position: relative; }





/*---------------------MEDIA QUERIES-----------------*/
/* Tablet (portrait) */
@media only screen and (min-width: 768px) and (max-width: 959px) {
	body { }
	.wrap {
		padding-right:1em;
		padding-left:1em;		
	}
}

/* Mobile (portrait) */
@media only screen and (max-width: 767px) {
	body {
		padding:0;
	}
	#content {
		width:60%;
	}
	#sidebar-primary {
		width:35%;
	}
	.wrap {
		padding-right:1em;
		padding-left:1em;		
	}
}

@media only screen and (max-width: 480px) {	
	body { }
	#content {
		width:100%;
	}
	#sidebar-primary {
		clear:both;
		width:100%;
	}
}
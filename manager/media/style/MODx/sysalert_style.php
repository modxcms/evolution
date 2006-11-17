<?php
/**
 * System Alert Box Styles
 * 
 * These styles are inserted inline with the system alert code,
 * that is why they are in a separate file.
 * 
 */
$sysalert_style =<<<EOD

/* System Alert Box
---------------------------------------------------------- */
		.cbOverlay {
			background-color: #000;
			z-index: 50000;
		}
		
		.cbContainer {
			padding:5px;
			background-color:white;
			z-index: 50000;
		}
		.cbBox h3 {
			color:#000;
			background: #9abbe5;
			padding:5px;
			font-weight:bold;
		}
		.cbBox p {
			margin:3px;
		}
		.cbBox .cbButtons {
			text-align:center;
		}
				
		.sysAlert {
			width: 500px;
			height: 330px;
			white-space: nowrap;
			overflow: auto;
		}
EOD;
?>
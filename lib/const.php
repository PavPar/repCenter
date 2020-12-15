<?php

// Order statuses
define("ST_ACCEPTED", 0);
define("ST_PROGRESS", 1);
define("ST_DONE", 2);
define("ST_RETURN", 3);
define("ST_REJECT", 4);

// Human-readable order statuses
define("STATUSES_NAMES", [
    ST_ACCEPTED => "Accepted",
    ST_PROGRESS => "In progress",
    ST_DONE => "Done",
    ST_RETURN => "Returned",
    ST_REJECT => "Rejected"
]);

// Install constant
define("WO_INSTALL_CONST_NAME", "WO_INSTALL_SCRIPT");
define("NO_XML_RENDER_CONST", "NO_XML_RENDER");
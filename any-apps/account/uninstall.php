<?php

$query = array();

$query[] = "DROP TABLE ".DB_PREFIX."user_profile;";

$query[] = "DROP TABLE ".DB_PREFIX."user_follow;";

return $query;
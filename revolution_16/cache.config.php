<?php
/************************************************************************/
/* DUNE by NPDS / SUPER-CACHE engine                                    */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/
/************************************************************************/
/*  Original Autor : Francisco Echarte [patxi@eslomas.com]              */
/*  Revision : 2004-03-15 Version: 1.1 / multi-language support by Dev  */
/*  Revision : 2004-08-10 Version: 1.2 / SQL support by Dev             */
/*  Revision : 2006-01-28 Version: 1.3 / .common support by Dev         */
/*  Revision : 2009-03-12 Version: 1.4 / clean_limit mods by Dev        */
/************************************************************************/

// Be sure that apache user have the permission to Read/Write/Delete in the Dir
$CACHE_CONFIG['data_dir'] = 'cache/';

// How the Auto_Cleanup process is run
// 0 no cleanup - 1 auto_cleanup
$CACHE_CONFIG['run_cleanup']  = 1;

// value between 1 and 100. The most important is the value, the most "probabilidad", cleanup process as chance to be runed
$CACHE_CONFIG['cleanup_freq'] = 20;

// maximum age - 24 Hours
$CACHE_CONFIG['max_age'] = 86400;

// Instant Stats
// 0 no - 1 Yes
$CACHE_CONFIG['save_stats'] = 0;

// Terminate send http process after sending cache page
// 0 no - 1 Yes
$CACHE_CONFIG['exit'] = 0;

// If the maximum number of "webuser" is ritched : SuperCache not clean the cache
// compare with the value store in cache/site_load.log updated by the site_load() function of mainfile.php
$CACHE_CONFIG['clean_limit'] = 300;

// Same standard cache (not the functions for members) for anonymous and members
// 0 no - 1 Yes
$CACHE_CONFIG['non_differentiate'] = 0;

?>
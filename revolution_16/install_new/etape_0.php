<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/* NPDS Copyright (c) 2002-2016 by Philippe Brunier                     */
/* IZ-Xinstall version : 1.2                                            */
/*                                                                      */
/* Auteurs : v.0.1.0 EBH (plan.net@free.fr)                             */
/*         : v.1.1.1 jpb, phr                                           */
/*         : v.1.1.2 jpb, phr, dev, boris                               */
/*         : v.1.1.3 dev - 2013                                         */
/*         : v.1.2 phr, jpb - 2016                                      */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
if (!stristr($_SERVER['PHP_SELF'],"install.php")) { die(); }

function etape_0()
{
 global $stage;
 $stage = 0;
 echo '<div class="row">
 <div class="col-md-3"></div>
 <div class="col-md-9">
 <form name="langue" method="post" action="install.php">
  <div class="form-group row">
    <label class="col-sm-4"><h3>Choix langue</h3></label>
    <div class="col-sm-8">
      <div class="m-y-1 radio">
        <label>
        <input class="m-y-1" type="radio" name="langue" value="french" checked="checked" />
          <h2><span class="label label-pill label-default">Français</span></h2>
        </label>
      </div>
      <div class="m-y-1 radio">
        <label>
          <input class="m-y-1" type="radio" name="langue" value="english" />
          <h2><span class="label label-pill label-default">English</span></h2>
        </label>
      </div>
      <div class="m-y-1 radio">
        <label>
          <input class="m-y-1" type="radio" name="langue" value="german" />
          <h2><span class="label label-pill label-default">Deutschland</span></h2>
        </label>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-8">
      <input type="hidden" name="stage" value="1" />
      <button type="submit" class="btn btn-warning-outline label-pill"><i class="fa fa-lg fa-check"></i> Ok </button>
    </div>
  </div>
</form></div>';
}
?>
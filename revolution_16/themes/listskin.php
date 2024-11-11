<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

   $lepath = dirname($_SERVER['PHP_SELF']);
   $rech = '#(.*\/)(themes\/_skins)\/(.*)#';
   preg_match_all($rech, $lepath, $result);

   $optskins=array('');
   $handle=opendir('../../../themes/_skins');
   while (false!==($file = readdir($handle))) {
      if ( (!strstr($file,'.')) and (!strstr($file,'bower_components')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
         $optskins[] = '<a class="dropdown-item" href="../'.$file.'">'.ucfirst ($file).'</a> ';
      }
   }
   closedir($handle);
   natcasesort($optskins);
   $optskins=implode(' ',$optskins);
   $skinpath = dirname($_SERVER['PHP_SELF']);
   $parts = explode('/', $skinpath);
   $skinametitre = end($parts);
   $headerclasses ='navbar navbar-expand-md bg-primary fixed-top';
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>NPDS <?php echo $skinametitre; ?> skin by Bootswatch</title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link rel="stylesheet" href="<?php echo implode($result[1]); ?>lib/font-awesome/css/all.min.css">
      <link rel="stylesheet" href="bootstrap.min.css" />
      <link rel="stylesheet" href="<?php echo implode($result[1]); ?>lib/css/prism.css" />
      <link rel="stylesheet" href="../assets/css/custom.min.css" />
      <link rel="stylesheet" href="<?php echo implode($result[1]); ?>lib/bootstrap/dist/css/bootstrap-icons.css">
      <style>
         .scrollable-menu {
           height: auto;
           max-height: 350px;
           overflow-x: hidden;
         }
         .scrollable-menu::-webkit-scrollbar {
           -webkit-appearance: none;
           width: 6px;
         }
         .scrollable-menu::-webkit-scrollbar-thumb {
           border-radius: 4px;
           background-color: lightgray;
           -webkit-box-shadow: 0 0 1px rgba(255,255,255,.75);
         }
      </style>
   </head>
   <body>
      <nav class="<?php echo $headerclasses; ?>" data-bs-theme="dark">
         <div class="container-fluid">
            <a class="navbar-brand" href="#"><img class="me-2" width="32" height="32" src="<?php echo implode($result[1]); ?>images/admin/message_npds.png" alt="logo_npds">NPDS skins</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barnav" aria-controls="barnav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="barnav">
               <ul class="nav navbar-nav">
                  <li class="nav-item"><a class="nav-link" href="<?php echo implode($result[1]); ?>"><i class="fa fa-home fa-lg"></i></a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo implode($result[1]); ?>user.php?op=chgtheme"><i class="fas fa-paint-brush fa-lg"></i></a></li>
                  <li class="nav-item dropdown" data-bs-theme="light">
                     <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false" id="skins">Skins</a>
                     <div class="dropdown-menu" aria-labelledby="skins"  role="menu">
                        <a class="dropdown-item" href="../default">Default</a>
                        <?php echo $optskins; ?>
                     </div>
                  </li>
                  <li class="nav-item dropdown" data-bs-theme="light">
                     <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">T&eacute;l&eacute;chargement</a>
                        <ul class="dropdown-menu">
                           <li><a class="dropdown-item" href="./bootstrap.css" target="_blank">bootstrap.css</a></li>
                           <li><a class="dropdown-item" href="./bootstrap.min.css" target="_blank">bootstrap.min.css</a></li>
                           <li><a class="dropdown-item" href="./extra.css" target="_blank">extra.css</a></li>
                           <li class="dropdown-divider"></li>
                           <li><a class="dropdown-item" href="./_variables.scss">_variables.scss</a></li>
                        </ul>
                     </li>
               </ul>
               <ul class="nav navbar-nav ms-auto">
                <li class="nav-item dropdown" data-bs-theme="light">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="theme-menu" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme">
                      <i class="bi bi-circle-half"></i>
                      <span class="d-lg-none ms-2">Toggle theme</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                          <i class="bi bi-sun-fill"></i><span class="ms-2">Light</span>
                        </button>
                      </li>
                      <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                          <i class="bi bi-moon-stars-fill"></i><span class="ms-2">Dark</span>
                        </button>
                      </li>
                    </ul>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="container">
         <div class="" style="background-position: 0px -65px;">
            <div class="page-header" id="banner">
               <div class="row d-flex align-items-center">
                  <div class="col-md-4 mb-3">
                     <h1><?php echo ucfirst($skinametitre); ?></h1>
                     <p class="lead">Nice skin for NPDS Cms</p>
                      <img class="img-fluid" src="../../../../themes/default/images/logo.png" alt="logo npds" loading="lazy" />
                  </div>
                  <div class="col-md-4 mb-3"></div>
                  <div class="col-md-4">
                     <div class="list-group">
                        <a class="list-group-item list-group-item-action" href="#navbars">Barre de navigation</a>
                        <a class="list-group-item list-group-item-action" href="#buttons">Boutons</a>
                        <a class="list-group-item list-group-item-action" href="#typography">Typographie</a>
                        <a class="list-group-item list-group-item-action" href="#tables">Tableaux</a>
                        <a class="list-group-item list-group-item-action" href="#forms">Formulaire</a>
                        <a class="list-group-item list-group-item-action" href="#navs">Navs</a>
                        <a class="list-group-item list-group-item-action" href="#indicators">Indicateurs</a>
                        <a class="list-group-item list-group-item-action" href="#progress">Barre de progression</a>
                        <a class="list-group-item list-group-item-action" href="#containers">Conteneurs</a>
                        <a class="list-group-item list-group-item-action" href="#dialogs">Dialogues</a>
                     </div>
                 </div>
              </div>
            </div>
         </div>
      </div>
      <div class="container">
         <!-- Navbar
         ================================================== -->
         <div class="bs-docs-section clearfix">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="navbars">Barre de navigation</h1>
               </div>
               <div class="bs-component">
                 <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
                    <div class="container-fluid">
                      <a class="navbar-brand" href="#">Navbar</a>
                      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="true" aria-label="Toggle navigation" style="">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="navbar-collapse collapse" id="navbarColor01" style="">
                        <ul class="navbar-nav me-auto">
                          <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="visually-hidden">(current)</span></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                          </li>
                        </ul>
                        <form class="d-flex">
                          <input class="form-control me-sm-2" type="text" placeholder="Search" />
                          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                        </form>
                      </div>
                   </div>
                 </nav>
               </div>
               <div class="bs-component">
                 <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
                   <div class="container-fluid">
                      <a class="navbar-brand" href="#">Navbar</a>
                      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="true" aria-label="Toggle navigation" style="">
                        <span class="navbar-toggler-icon"></span>
                      </button>
                      <div class="navbar-collapse collapse show" id="navbarColor02" style="">
                        <ul class="navbar-nav me-auto">
                          <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="visually-hidden">(current)</span></a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                          </li>
                        </ul>
                        <form class="d-flex">
                          <input class="form-control me-sm-2" type="text" placeholder="Search">
                          <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                        </form>
                      </div>
                   </div>
                 </nav>
               </div>
               <div class="bs-component">
                 <nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
                  <div class="container-fluid">
                   <a class="navbar-brand" href="#">Navbar</a>
                   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="true" aria-label="Toggle navigation" style="">
                     <span class="navbar-toggler-icon"></span>
                   </button>
                   <div class="navbar-collapse collapse show" id="navbarColor03" style="">
                     <ul class="navbar-nav me-auto">
                       <li class="nav-item active">
                         <a class="nav-link" href="#">Home <span class="visually-hidden">(current)</span></a>
                       </li>
                       <li class="nav-item">
                         <a class="nav-link" href="#">Features</a>
                       </li>
                       <li class="nav-item">
                         <a class="nav-link" href="#">Pricing</a>
                       </li>
                       <li class="nav-item">
                         <a class="nav-link" href="#">About</a>
                       </li>
                     </ul>
                     <form class="d-flex">
                       <input class="form-control me-sm-2" type="text" placeholder="Search">
                       <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                     </form>
                   </div>
                   </div>
                 </nav>
               </div>
             </div>
           </div>
         </div>
         <!-- Boutons
         ================================================== -->
         <div class="bs-docs-section">
           <div class="page-header">
             <div class="row">
               <div class="col-lg-12">
                 <h1 id="buttons">Boutons</h1>
               </div>
             </div>
           </div>

           <div class="row">
             <div class="col-lg-7">
               <p class="bs-component">
                 <button type="button" class="btn btn-primary">Primary</button>
                 <button type="button" class="btn btn-secondary">Secondary</button>
                 <button type="button" class="btn btn-success">Success</button>
                 <button type="button" class="btn btn-info">Info</button>
                 <button type="button" class="btn btn-warning">Warning</button>
                 <button type="button" class="btn btn-danger">Danger</button>
                 <button type="button" class="btn btn-link">Link</button>
               </p>
               <p class="bs-component">
                 <button type="button" class="btn btn-primary disabled">Primary</button>
                 <button type="button" class="btn btn-secondary disabled">Secondary</button>
                 <button type="button" class="btn btn-success disabled">Success</button>
                 <button type="button" class="btn btn-info disabled">Info</button>
                 <button type="button" class="btn btn-warning disabled">Warning</button>
                 <button type="button" class="btn btn-danger disabled">Danger</button>
                 <button type="button" class="btn btn-link disabled">Link</button>
               </p>
               <p class="bs-component">
                 <button type="button" class="btn btn-outline-primary">Primary</button>
                 <button type="button" class="btn btn-outline-secondary">Secondary</button>
                 <button type="button" class="btn btn-outline-success">Success</button>
                 <button type="button" class="btn btn-outline-info">Info</button>
                 <button type="button" class="btn btn-outline-warning">Warning</button>
                 <button type="button" class="btn btn-outline-danger">Danger</button>
               </p>
               <div class="bs-component">
                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                   <button type="button" class="btn btn-primary">Primary</button>
                   <div class="btn-group" role="group">
                     <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                     <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" data-popper-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                       <a class="dropdown-item" href="#">Dropdown link</a>
                       <a class="dropdown-item" href="#">Dropdown link</a>
                     </div>
                   </div>
                 </div>

                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                   <button type="button" class="btn btn-success">Success</button>
                   <div class="btn-group" role="group">
                     <button id="btnGroupDrop2" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                     <div class="dropdown-menu" aria-labelledby="btnGroupDrop2" data-popper-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                       <a class="dropdown-item" href="#">Dropdown link</a>
                       <a class="dropdown-item" href="#">Dropdown link</a>
                     </div>
                   </div>
                 </div>

                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                   <button type="button" class="btn btn-info">Info</button>
                   <div class="btn-group" role="group">
                     <button id="btnGroupDrop3" type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                     <div class="dropdown-menu" aria-labelledby="btnGroupDrop3" data-popper-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                       <a class="dropdown-item" href="#">Dropdown link</a>
                       <a class="dropdown-item" href="#">Dropdown link</a>
                     </div>
                   </div>
                 </div>

                 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                   <button type="button" class="btn btn-danger">Danger</button>
                   <div class="btn-group" role="group">
                     <button id="btnGroupDrop4" type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                     <div class="dropdown-menu" aria-labelledby="btnGroupDrop4" data-popper-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                       <a class="dropdown-item" href="#">Dropdown link</a>
                       <a class="dropdown-item" href="#">Dropdown link</a>
                     </div>
                   </div>
                 </div>
               </div>
               <div class="bs-component">
                 <button type="button" class="btn btn-primary btn-lg">Large button</button>
                 <button type="button" class="btn btn-primary">Default button</button>
                 <button type="button" class="btn btn-primary btn-sm">Small button</button>
               </div>
             </div>
             <div class="col-lg-5">

               <p class="bs-component">
                 <button type="button" class="btn btn-primary btn-lg btn-block">Block level button</button>
               </p>

               <div class="bs-component" style="margin-bottom: 15px;">
                 <div class="btn-group btn-group-toggle" data-bs-toggle="buttons">
                   <label class="btn btn-primary active">
                     <input type="checkbox" checked="" autocomplete="off"> Active
                   </label>
                   <label class="btn btn-primary">
                     <input type="checkbox" autocomplete="off"> Check
                   </label>
                   <label class="btn btn-primary">
                     <input type="checkbox" autocomplete="off"> Check
                   </label>
                 </div>
               </div>

               <div class="bs-component" style="margin-bottom: 15px;">
                 <div class="btn-group btn-group-toggle" data-bs-toggle="buttons">
                   <label class="btn btn-primary active">
                     <input type="radio" name="options" id="option1" autocomplete="off" checked=""> Active
                   </label>
                   <label class="btn btn-primary">
                     <input type="radio" name="options" id="option2" autocomplete="off"> Radio
                   </label>
                   <label class="btn btn-primary">
                     <input type="radio" name="options" id="option3" autocomplete="off"> Radio
                   </label>
                 </div>
               </div>

               <div class="bs-component">
                 <div class="btn-group-vertical" data-bs-toggle="buttons">
                   <button type="button" class="btn btn-primary">Button</button>
                   <button type="button" class="btn btn-primary">Button</button>
                   <button type="button" class="btn btn-primary">Button</button>
                   <button type="button" class="btn btn-primary">Button</button>
                   <button type="button" class="btn btn-primary">Button</button>
                   <button type="button" class="btn btn-primary">Button</button>
                 </div>
               </div>

               <div class="bs-component" style="margin-bottom: 15px;">
                 <div class="btn-group" role="group" aria-label="Basic example">
                   <button type="button" class="btn btn-secondary">Left</button>
                   <button type="button" class="btn btn-secondary">Middle</button>
                   <button type="button" class="btn btn-secondary">Right</button>
                 </div>
               </div>

               <div class="bs-component" style="margin-bottom: 15px;">
                 <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                   <div class="btn-group me-2" role="group" aria-label="First group">
                     <button type="button" class="btn btn-secondary">1</button>
                     <button type="button" class="btn btn-secondary">2</button>
                     <button type="button" class="btn btn-secondary">3</button>
                     <button type="button" class="btn btn-secondary">4</button>
                   </div>
                   <div class="btn-group me-2" role="group" aria-label="Second group">
                     <button type="button" class="btn btn-secondary">5</button>
                     <button type="button" class="btn btn-secondary">6</button>
                     <button type="button" class="btn btn-secondary">7</button>
                   </div>
                   <div class="btn-group" role="group" aria-label="Third group">
                     <button type="button" class="btn btn-secondary">8</button>
                   </div>
                 </div>
               </div>

             </div>
           </div>
         </div>
         <!-- Typographie
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="typography">Typographie</h1>
               </div>
             </div>
           </div>

           <!-- Headings -->

           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
                 <h1>Heading 1</h1>
                 <h2>Heading 2</h2>
                 <h3>Heading 3</h3>
                 <h4>Heading 4</h4>
                 <h5>Heading 5</h5>
                 <h6>Heading 6</h6>
                 <h3>
                   Heading
                   <small class="text-body-secondary">with faded secondary text</small>
                 </h3>
                 <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
               <div class="source-button btn btn-primary btn-sm" style="display: none;">&lt; &gt;</div></div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <h2>Example body text</h2>
                 <p>Nullam quis risus eget <a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
                 <p><small>This line of text is meant to be treated as fine print.</small></p>
                 <p>The following is <strong>rendered as bold text</strong>.</p>
                 <p>The following is <em>rendered as italicized text</em>.</p>
                 <p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
               </div>

             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                  <h2>Emphasis classes</h2>
                  <p class="text-primary">.text-primary</p>
                  <p class="text-primary-emphasis">.text-primary-emphasis</p>
                  <p class="text-secondary">.text-secondary</p>
                  <p class="text-secondary-emphasis">.text-secondary-emphasis</p>
                  <p class="text-success">.text-success</p>
                  <p class="text-success-emphasis">.text-success-emphasis</p>
                  <p class="text-danger">.text-danger</p>
                  <p class="text-danger-emphasis">.text-danger-emphasis</p>
                  <p class="text-warning">.text-warning</p>
                  <p class="text-warning-emphasis">.text-warning-emphasis</p>
                  <p class="text-info">.text-info</p>
                  <p class="text-info">.text-info-emphasis</p>
                  <p class="text-light">.text-light</p>
                  <p class="text-light">.text-light-emphasis</p>
                  <p class="text-dark">.text-dark</p>
                  <p class="text-dark">.text-dark-emphasis</p>
                  <p class="text-body">.text-body</p>
                  <p class="text-body">.text-body-emphasis</p>
                  <p class="text-body-secondary">.text-body-secondary</p>
                  <p class="text-body-tertiary">.text-body-tertiary</p>
               </div>
             </div>
           </div>

           <!-- Blockquotes -->

           <div class="row">
             <div class="col-lg-12">
               <h2 id="type-blockquotes">Blockquotes</h2>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
               <figure>
                  <blockquote class="blockquote">
                     <p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
               </figure>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
               <figure>
                  <blockquote class="blockquote text-center">
                     <p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
               </figure>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                  <figure>
                     <blockquote class="blockquote text-end">
                        <p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                     </blockquote>
                     <figcaption class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
                  </figure>
               </div>
             </div>
           </div>
         </div>
         <!-- Tableaux
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="tables">Tableaux</h1>
               </div>
               <div class="bs-component">
                 <table class="table table-hover">
                   <thead>
                     <tr>
                       <th scope="col">Type</th>
                       <th scope="col">Ent&ecirc;te colonne</th>
                       <th scope="col">Ent&ecirc;te colonne</th>
                       <th scope="col">Ent&ecirc;te colonne</th>
                     </tr>
                   </thead>
                   <tbody>
                     <tr class="table-active">
                       <th scope="row">Active</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr>
                       <th scope="row">Default</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-primary">
                       <th scope="row">Primary</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-secondary">
                       <th scope="row">Secondary</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-success">
                       <th scope="row">Success</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-danger">
                       <th scope="row">Danger</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-warning">
                       <th scope="row">Warning</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-info">
                       <th scope="row">Info</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-light">
                       <th scope="row">Light</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                     <tr class="table-dark">
                       <th scope="row">Dark</th>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                       <td>Contenu colonne</td>
                     </tr>
                   </tbody>
                 </table> 
               </div>
             </div>
           </div>
         </div>
         <!-- Formulaire
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="forms">Formulaires</h1>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-6">
               <div class="bs-component">
                  <form>
                    <fieldset>
                      <legend>Legend</legend>
                      <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="text" readonly="" class="form-control-plaintext" id="staticEmail" value="email@example.com">
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label mt-4">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                        <small id="emailHelp" class="form-text text-body-secondary">We'll never share your email with anyone else.</small>
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label mt-4">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                      </div>
                      <div class="mb-3">
                        <label for="exampleSelect1" class="form-label mt-4">Example select</label>
                        <select class="form-select" id="exampleSelect1">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="exampleSelect2" class="form-label mt-4">Example multiple select</label>
                        <select multiple="" class="form-select" id="exampleSelect2">
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="exampleTextarea" class="form-label mt-4">Example textarea</label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                      </div>
                      <div class="mb-3">
                        <label for="formFile" class="form-label mt-4">Default file input example</label>
                        <input class="form-control" type="file" id="formFile">
                      </div>
                      <fieldset class="mb-3">
                        <legend class="mt-4">Radio buttons</legend>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                            Option one is this and that—be sure to include why it's great
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">
                            Option two can be something else and selecting it will deselect option one
                          </label>
                        </div>
                        <div class="form-check disabled">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled="">
                            Option three is disabled
                          </label>
                        </div>
                      </fieldset>
                      <fieldset class="mb-3">
                        <legend class="mt-4">Checkboxes</legend>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckDefault">
                            Default checkbox
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked="">
                          <label class="form-check-label" for="flexCheckChecked">
                            Checked checkbox
                          </label>
                        </div>
                      </fieldset>
                      <fieldset>
                        <legend class="mt-4">Switches</legend>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
                        </div>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                          <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
                        </div>
                      </fieldset>
                      <fieldset class="mb-3">
                        <legend class="mt-4">Ranges</legend>
                          <label for="customRange1" class="form-label">Example range</label>
                          <input type="range" class="form-range" id="customRange1">
                          <label for="disabledRange" class="form-label">Disabled range</label>
                          <input type="range" class="form-range" id="disabledRange" disabled="">
                          <label for="customRange3" class="form-label">Example range</label>
                          <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                      </fieldset>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </fieldset>
                  </form>
               </div>
             </div>
             <div class="col-lg-4 offset-lg-1">
               <form class="bs-component">
                  <div class="mb-3">
                    <fieldset disabled="">
                      <label class="form-label" for="disabledInput">Disabled input</label>
                      <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="" />
                    </fieldset>
                  </div>
                  <div class="mb-3">
                    <fieldset>
                      <label class="form-label mt-4" for="readOnlyInput">Readonly input</label>
                      <input class="form-control" id="readOnlyInput" type="text" placeholder="Readonly input here..." readonly="" />
                    </fieldset>
                  </div>
                  <div class="mb-3 has-success">
                    <label class="form-label mt-4" for="inputValid">Valid input</label>
                    <input type="text" value="correct value" class="form-control is-valid" id="inputValid" />
                    <div class="valid-feedback">Success! You've done it.</div>
                  </div>
                  <div class="mb-3 has-danger">
                    <label class="form-label mt-4" for="inputInvalid">Invalid input</label>
                    <input type="text" value="wrong value" class="form-control is-invalid" id="inputInvalid" />
                    <div class="invalid-feedback">Sorry, that username's taken. Try another?</div>
                  </div>
                  <div class="mb-3">
                    <label class="col-form-label col-form-label-lg mt-4" for="inputLarge">Large input</label>
                    <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" id="inputLarge" />
                  </div>
                  <div class="mb-3">
                    <label class="col-form-label mt-4" for="inputDefault">Default input</label>
                    <input type="text" class="form-control" placeholder="Default input" id="inputDefault" />
                  </div>
                  <div class="mb-3">
                    <label class="col-form-label col-form-label-sm mt-4" for="inputSmall">Small input</label>
                    <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" id="inputSmall" />
                  </div>
                  <div class="mb-3">
                    <label class="form-label mt-4">Input addons</label>
                    <div class="mb-3">
                      <div class="input-group mb-3">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" />
                        <span class="input-group-text">.00</span>
                      </div>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2" />
                        <button class="btn btn-primary" type="button" id="button-addon2">Button</button>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label mt-4">Floating labels</label>
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" />
                      <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" />
                      <label for="floatingPassword">Password</label>
                    </div>
                  </div>
               </form>
             </div>
           </div>
         </div>
         <!-- Navs
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="navs">Navs</h1>
               </div>
             </div>
           </div>
           <div class="row" style="margin-bottom: 2rem;">
             <div class="col-lg-6">
               <h2 id="nav-tabs">Tabs</h2>
               <div class="bs-component">
                 <ul class="nav nav-tabs">
                   <li class="nav-item">
                     <a class="nav-link active" data-bs-toggle="tab" href="#home">Home</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" data-bs-toggle="tab" href="#profile">Profile</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link disabled" href="#">Disabled</a>
                   </li>
                   <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                     <div class="dropdown-menu">
                       <a class="dropdown-item" href="#">Action</a>
                       <a class="dropdown-item" href="#">Another action</a>
                       <a class="dropdown-item" href="#">Something else here</a>
                       <div class="dropdown-divider"></div>
                       <a class="dropdown-item" href="#">Separated link</a>
                     </div>
                   </li>
                 </ul>
                 <div id="myTabContent" class="tab-content">
                   <div class="tab-pane fade show active" id="home">
                     <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                   </div>
                   <div class="tab-pane fade" id="profile">
                     <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                   </div>
                   <div class="tab-pane fade" id="dropdown1">
                     <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
                   </div>
                   <div class="tab-pane fade" id="dropdown2">
                     <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
                   </div>
                 </div>
               </div>
             </div>

             <div class="col-lg-6">
               <h2 id="nav-pills">Pills</h2>
               <div class="bs-component">
                 <ul class="nav nav-pills">
                   <li class="nav-item">
                     <a class="nav-link active" href="#">Active</a>
                   </li>
                   <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                     <div class="dropdown-menu">
                       <a class="dropdown-item" href="#">Action</a>
                       <a class="dropdown-item" href="#">Another action</a>
                       <a class="dropdown-item" href="#">Something else here</a>
                       <div class="dropdown-divider"></div>
                       <a class="dropdown-item" href="#">Separated link</a>
                     </div>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" href="#">Link</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link disabled" href="#">Disabled</a>
                   </li>
                 </ul>
               </div>
               <br>
               <div class="bs-component">
                 <ul class="nav nav-pills flex-column">
                   <li class="nav-item">
                     <a class="nav-link active" href="#">Active</a>
                   </li>
                   <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                     <div class="dropdown-menu">
                       <a class="dropdown-item" href="#">Action</a>
                       <a class="dropdown-item" href="#">Another action</a>
                       <a class="dropdown-item" href="#">Something else here</a>
                       <div class="dropdown-divider"></div>
                       <a class="dropdown-item" href="#">Separated link</a>
                     </div>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" href="#">Link</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link disabled" href="#">Disabled</a>
                   </li>
                 </ul>
               </div>
             </div>
           </div>

           <div class="row">
             <div class="col-lg-6">
               <h2 id="nav-breadcrumbs">Breadcrumbs</h2>
               <div class="bs-component">
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item active">Home</li>
                 </ol>
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#">Home</a></li>
                   <li class="breadcrumb-item active">Library</li>
                 </ol>
                 <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="#">Home</a></li>
                   <li class="breadcrumb-item"><a href="#">Library</a></li>
                   <li class="breadcrumb-item active">Data</li>
                 </ol>
               <div class="source-button btn btn-primary btn-sm" style="display: none;">&lt; &gt;</div></div>
             </div>

             <div class="col-lg-6">
               <h2 id="pagination">Pagination</h2>
               <div class="bs-component">
                 <div>
                   <ul class="pagination">
                     <li class="page-item disabled">
                       <a class="page-link" href="#">«</a>
                     </li>
                     <li class="page-item active">
                       <a class="page-link" href="#">1</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">2</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">3</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">4</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">5</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">»</a>
                     </li>
                   </ul>
                 </div>

                 <div>
                   <ul class="pagination pagination-lg">
                     <li class="page-item disabled">
                       <a class="page-link" href="#">«</a>
                     </li>
                     <li class="page-item active">
                       <a class="page-link" href="#">1</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">2</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">3</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">4</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">5</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">»</a>
                     </li>
                   </ul>
                 </div>

                 <div>
                   <ul class="pagination pagination-sm">
                     <li class="page-item disabled">
                       <a class="page-link" href="#">«</a>
                     </li>
                     <li class="page-item active">
                       <a class="page-link" href="#">1</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">2</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">3</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">4</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">5</a>
                     </li>
                     <li class="page-item">
                       <a class="page-link" href="#">»</a>
                     </li>
                   </ul>
                 </div>

               </div>
             </div>
           </div>
         </div>
         <!-- Indicateurs
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="indicators">Indicateurs</h1>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-12">
               <h2>Alertes</h2>
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-warning">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <h4 class="alert-heading">Warning!</h4>
                   <p class="mb-0">Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-danger">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-success">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-info">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-primary">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-secondary">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="alert alert-dismissible alert-light">
                   <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                   <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
                 </div>
               </div>
             </div>
           </div>
           <div>
             <h2>Badges</h2>
             <div class="bs-component" style="margin-bottom: 40px;">
               <span class="badge bg-primary">Primary</span>
               <span class="badge bg-secondary">Secondary</span>
               <span class="badge bg-success">Success</span>
               <span class="badge bg-danger">Danger</span>
               <span class="badge bg-warning">Warning</span>
               <span class="badge bg-info">Info</span>
               <span class="badge bg-light">Light</span>
               <span class="badge bg-dark">Dark</span>
             <div class="source-button btn btn-primary btn-sm" style="display: none;">&lt; &gt;</div></div>
             <div class="bs-component">
               <span class="badge rounded-pill bg-primary">Primary</span>
               <span class="badge rounded-pill bg-secondary">Secondary</span>
               <span class="badge rounded-pill bg-success">Success</span>
               <span class="badge rounded-pill bg-danger">Danger</span>
               <span class="badge rounded-pill bg-warning">Warning</span>
               <span class="badge rounded-pill bg-info">Info</span>
               <span class="badge rounded-pill bg-light">Light</span>
               <span class="badge rounded-pill bg-dark">Dark</span>
             </div>
           </div>
         </div>
         <!-- Progress
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="progress">Barre de progression</h1>
               </div>
               <h3 id="progress-basic">Basique</h3>
               <div class="bs-component">
                 <div class="progress">
                   <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
               </div>
               <h3 id="progress-alternatives">Alternatives contextuelles</h3>
               <div class="bs-component">
                 <div class="progress">
                   <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
               <div class="source-button btn btn-primary btn-sm" style="display: none;">&lt; &gt;</div></div>
               <h3 id="progress-multiple">Multiple</h3>
               <div class="bs-component">
                 <div class="progress">
                   <div class="progress-bar" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                   <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                   <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
               </div>
               <h3 id="progress-striped">Hachurée</h3>
               <div class="bs-component">
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                 </div>
               </div>
               <h3 id="progress-animated">Anim&eacute;e</h3>
               <div class="bs-component">
                 <div class="progress">
                   <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                 </div>
               </div>
             </div>
           </div>
         </div>
         <!-- Conteneurs
         ================================================== -->
         <div class="bs-docs-section">
           <div class="row">
             <div class="col-lg-12">
               <div class="page-header">
                 <h1 id="containers">Conteneurs</h1>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-12">
               <h2 class="my-3">List groups</h2>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
                 <ul class="list-group">
                   <li class="list-group-item d-flex justify-content-between align-items-center">
                     Cras justo odio
                     <span class="badge bg-primary rounded-pill">14</span>
                   </li>
                   <li class="list-group-item d-flex justify-content-between align-items-center">
                     Dapibus ac facilisis in
                     <span class="badge bg-primary rounded-pill">2</span>
                   </li>
                   <li class="list-group-item d-flex justify-content-between align-items-center">
                     Morbi leo risus
                     <span class="badge bg-primary rounded-pill">1</span>
                   </li>
                 </ul>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="list-group">
                   <a href="#" class="list-group-item list-group-item-action active">
                     Cras justo odio
                   </a>
                   <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in
                   </a>
                   <a href="#" class="list-group-item list-group-item-action disabled">Morbi leo risus
                   </a>
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="list-group">
                   <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
                     <div class="d-flex w-100 justify-content-between">
                       <h5 class="mb-1">List group item heading</h5>
                       <small>3 days ago</small>
                     </div>
                     <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                     <small>Donec id elit non mi porta.</small>
                   </a>
                   <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                     <div class="d-flex w-100 justify-content-between">
                       <h5 class="mb-1">List group item heading</h5>
                       <small class="text-body-secondary">3 days ago</small>
                     </div>
                     <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                     <small class="text-body-secondary">Donec id elit non mi porta.</small>
                   </a>
                 </div>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-12">
               <h2 class="my-3">Cards</h2>
             </div>
           </div>
           <div class="row">
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Primary card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-secondary mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Secondary card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-success mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Success card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-danger mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Danger card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-warning mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Warning card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Info card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card bg-light mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Light card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card text-white bg-dark mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Dark card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
               </div>
             </div>
             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="card border-primary mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-primary">
                     <h4 class="card-title">Primary card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-secondary mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-secondary">
                     <h4 class="card-title">Secondary card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-success mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-success">
                     <h4 class="card-title">Success card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-danger mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-danger">
                     <h4 class="card-title">Danger card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-warning mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-warning">
                     <h4 class="card-title">Warning card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-info mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-info">
                     <h4 class="card-title">Info card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-light mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body">
                     <h4 class="card-title">Light card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
                 <div class="card border-dark mb-3" style="max-width: 20rem;">
                   <div class="card-header">Header</div>
                   <div class="card-body text-dark">
                     <h4 class="card-title">Dark card title</h4>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                 </div>
               <div class="source-button btn btn-primary btn-sm" style="display: none;">&lt; &gt;</div></div>
             </div>

             <div class="col-lg-4">
               <div class="bs-component">
                 <div class="card mb-3">
                   <h3 class="card-header">Card header</h3>
                   <div class="card-body">
                     <h5 class="card-title">Special title treatment</h5>
                     <h6 class="card-subtitle text-body-secondary">Support card subtitle</h6>
                   </div>
                   <img style="height: 200px; width: 100%; display: block;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22318%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20318%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_158bd1d28ef%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A16pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_158bd1d28ef%22%3E%3Crect%20width%3D%22318%22%20height%3D%22180%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22129.359375%22%20y%3D%2297.35%22%3EImage%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Card image">
                   <div class="card-body">
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                   </div>
                   <ul class="list-group list-group-flush">
                     <li class="list-group-item">Cras justo odio</li>
                     <li class="list-group-item">Dapibus ac facilisis in</li>
                     <li class="list-group-item">Vestibulum at eros</li>
                   </ul>
                   <div class="card-body">
                     <a href="#" class="card-link">Card link</a>
                     <a href="#" class="card-link">Another link</a>
                   </div>
                   <div class="card-footer text-body-secondary">
                     2 days ago
                   </div>
                 </div>
                 <div class="card">
                   <div class="card-body">
                     <h4 class="card-title">Card title</h4>
                     <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                     <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                     <a href="#" class="card-link">Card link</a>
                     <a href="#" class="card-link">Another link</a>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>
         <!-- Dialogues
         ================================================== -->
         <div class="bs-docs-section">
            <div class="row">
               <div class="col-lg-12">
                  <div class="page-header">
                     <h1 id="dialogs">Dialogues</h1>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-6">
                  <h2 class="my-3">Modals</h2>
                  <div class="bs-component">
                     <div class="modal">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Modal body text goes here.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
               <h2 class="my-3">Off canvas</h2>
               <div class="bs-component">
                  <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                    Link with href
                  </a>
                  <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    Button with data-bs-target
                  </button>
                  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <div>
                        Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                      </div>
                      <div class="dropdown mt-3">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                          Dropdown button
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item" href="#">Action</a></li>
                          <li><a class="dropdown-item" href="#">Autre action</a></li>
                          <li><a class="dropdown-item" href="#">Autre chose ici</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
               </div>
            </div>
            
               <div class="col-lg-6">
               <h2 class="my-3">Popovers</h2>
               <div class="bs-component" style="margin-bottom: 3em;">
                 <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="Popover Title">Gauche</button>
                 <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="Popover Title">Haut</button>
                 <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="Popover Title">Bas</button>
                 <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus." title="Popover Title">Droite</button>
               </div>
               <h2>Tooltips</h2>
               <div class="bs-component" style="margin-bottom: 3em;">
                 <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip à gauche">Gauche</button>
                 <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip en haut">Haut</button>
                 <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip en bas">Bas</button>
                 <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip à droite">Droite</button>
               </div>
               <h2>Toasts</h2>
               <div class="bs-component">
                 <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                   <div class="toast-header">
                     <strong class="me-auto">Bootstrap</strong>
                     <small>11 mins ago</small>
                     <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close">
                       <span aria-hidden="true"></span>
                     </button>
                   </div>
                   <div class="toast-body">
                     Hello, world! This is a toast message.
                   </div>
                 </div>
               <button class="source-button btn btn-primary btn-xs" role="button" tabindex="0"><i class="bi bi-code"></i></button></div>
             </div>
           </div>
         </div>
      </div>
      <div id="source-modal" class="modal fade">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title">Code source</h4>
                  <button type="button" class="btn btn-primary btn-copy"><i class="bi bi-clipboard"></i> Copier</button>
               </div>
               <div class="modal-body">
                  <pre class="language-html"><code></code></pre>
               </div>
            </div>
         </div>
      </div>

      <footer class="mt-5 px-2">
        <div class="row">
          <div class="col-sm-12">
            <ul class="list-unstyled">
              <li class="float-end"><a class="btn btn-outline-primary" href="#top" title="Top" data-bs-toggle="tooltip"><i class="bi bi-chevron-double-up"></i></a></li>
            </ul>
            <p>Made with Bootswatch by <a href="http://thomaspark.co" rel="nofollow">Thomas Park</a>. Contact him at <a href="mailto:thomas@bootswatch.com">thomas@bootswatch.com</a>.</p>
            <p>Npds fork by <a href="#" rel="nofollow">Jpb</a>. Contact him at <a href="mailto:jpb@npds.org">jpb@npds.org</a>.</p>
            <p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/gh-pages/LICENSE">MIT License</a>.</p>
            <p>Based on <a href="http://getbootstrap.com" rel="nofollow">Bootstrap 5</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/" rel="nofollow">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts" rel="nofollow">Google</a>.</p>
          </div>
        </div>
      </footer>

      <script type="text/javascript" src="<?php echo implode($result[1]); ?>lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script type="text/javascript" src="<?php echo implode($result[1]); ?>lib/js/prism.js"></script>
      <script type="text/javascript" src="../assets/js/custom.js"></script>
   </body>
</html>
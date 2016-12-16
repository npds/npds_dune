<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/* ===========================                                          */
/*                                                                      */
/*                                                                      */
/* NPDS Copyright (c) 2002-2015 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

   if (isset($user)) {
      if ($cookie[9]=='') $cookie[9]=$Default_Theme;
      if (isset($theme)) $cookie[9]=$theme;
      $tmp_theme=$cookie[9];
      if (!$file=@opendir("themes/$cookie[9]")) {
         $tmp_theme=$Default_Theme;
      }
   } else {
      $tmp_theme=$Default_Theme;
   }
   $optskins='';
   $handle=opendir('../../../themes/_skins');
   while (false!==($file = readdir($handle))) {
      if ( (!strstr($file,'.')) and (!strstr($file,'bower_components')) and (!strstr($file,'assets')) and (!strstr($file,'fonts')) ) {
         $optskins[] = '<li><a class="dropdown-item" href="../'.$file.'">'.ucfirst ($file).'</a></li> ';
      }
   }
   closedir($handle);
   natcasesort($optskins);
   $optskins=implode(' ',$optskins);
   $skinpath = dirname($_SERVER['PHP_SELF']);
   $parts = explode('/', $skinpath);
   $skinametitre = end($parts);
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>Bootswatch skins for NPDS - skin : <?php echo $skinametitre; ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link rel="stylesheet" href="../../../../lib/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="bootstrap.min.css" />
      <link rel="stylesheet" href="../assets/css/custom.min.css" />
   </head>
   <body>
      <div class="navbar navbar-dark navbar-fixed-top bg-primary">
         <div class="container">
            <div class="clearfix">
               <button class="navbar-toggler float-xs-right hidden-sm-up" type="button" data-toggle="collapse" data-target="#barnav"></button>
               <a href="#" class="navbar-brand hidden-sm-up" >NPDS</a>
            </div>
            <div class="collapse navbar-toggleable-xs" id="barnav">
               <ul class="nav navbar-nav">
                  <li class="nav-item active hidden-xs-down"><a class="nav-link" href="../">NPDS</a></li>
                  <li class="navbar-divider"></li>
                  <li class="nav-item"><a class="nav-link" href="../../../../"><i class="fa fa-home fa-lg"></i></a></li>
                  <li class="navbar-divider"></li>
                  <li class="nav-item dropdown">
                     <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Themes</a>
                     <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="#">npds-boost</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">other npds themes</a></li>
                     </ul>
                  </li>
                  <li class="navbar-divider"></li>
                  <li class="nav-item dropdown">
                     <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" id="skins">Skins</a>
                     <ul class="dropdown-menu scrollable-menu" role="menu">
                        <li><a class="dropdown-item" href="../default">Default</a></li>
                        <li class="dropdown-divider"></li>
                        <?php echo $optskins; ?>
                     </ul>
                  </li>
                  <li class="navbar-divider"></li>
                  <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                  <li class="navbar-divider"></li>
                  <li class="nav-item dropdown">
                     <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Download</a>
                        <ul class="dropdown-menu">
                           <li><a class="dropdown-item" href="./bootstrap.css" target="_blank">bootstrap.css</a></li>
                           <li><a class="dropdown-item" href="./bootstrap.min.css" target="_blank">bootstrap.min.css</a></li>
                           <li><a class="dropdown-item" href="./extra.css" target="_blank">extra.css</a></li>
                           <li class="dropdown-divider"></li>
                           <li><a class="dropdown-item" href="._variables.scss">_variables.scss</a></li>
                        </ul>
                     </li>
                </ul>
               <ul class="nav navbar-nav float-xs-right">
                  <li class="nav-item"><a class="nav-link" href="http://bootswatch.com/" target="_blank">Built With Bootswatch</a></li>
               </ul>
            </div>
         </div>
      </div>
      <div class="container">
         <div class="" style="background-position: 0px -65px;">
            <div class="page-header" id="banner">
              <div class="row">
                <div class="col-lg-8 col-md-7 col-sm-6">
                  <h1><?php echo ucfirst($skinametitre); ?></h1>
                  <p class="lead">Nice skin for NPDS Cms</p>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-6">
                  <div class="sponsor">
                      <img class="img-fluid" src="../../../../themes/npds-boost_sk/images/header.png" alt="npds" />
                  </div>
                </div>
              </div>
            </div>
         </div>
      </div>
      <div class="container">


        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-4">
            <div class="list-group table-of-contents">
              <a class="list-group-item list-group-item-action" href="#navbars">Navbars</a>
              <a class="list-group-item list-group-item-action" href="#buttons">Buttons</a>
              <a class="list-group-item list-group-item-action" href="#typography">Typography</a>
              <a class="list-group-item list-group-item-action" href="#tables">Tables</a>
              <a class="list-group-item list-group-item-action" href="#forms">Forms</a>
              <a class="list-group-item list-group-item-action" href="#navs">Navs</a>
              <a class="list-group-item list-group-item-action" href="#indicators">Indicators</a>
              <a class="list-group-item list-group-item-action" href="#progress">Progress</a>
              <a class="list-group-item list-group-item-action" href="#containers">Containers</a>
              <a class="list-group-item list-group-item-action" href="#dialogs">Dialogs</a>
            </div>
          </div>
        </div>
        
      <!-- Navbar
      ================================================== -->
      <div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="navbars">Navbars</h1>
            </div>

            <div class="bs-component">
              <nav class="navbar navbar-dark bg-primary">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarResponsive2" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></button>
                <div class="container collapse navbar-toggleable-md" id="navbarResponsive2">
                  <a class="navbar-brand" href="#">Navbar</a>
                  <ul class="nav navbar-nav">
                    <li class="nav-item active">
                      <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="http://example.com" id="supportedContentDropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                      <div class="dropdown-menu" aria-labelledby="supportedContentDropdown2">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </li>
                  </ul>
                  <form class="form-inline float-lg-right">
                    <input class="form-control" type="text" placeholder="Search">
                    <button class="btn btn-secondary" type="submit">Search</button>
                  </form>
                </div>
              </nav>
            </div>

            <div class="bs-component">
              <nav class="navbar navbar-dark bg-inverse">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarResponsive3" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></button>
                <div class="container collapse navbar-toggleable-md" id="navbarResponsive3">
                  <a class="navbar-brand" href="#">Navbar</a>
                  <ul class="nav navbar-nav">
                    <li class="nav-item active">
                      <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="http://example.com" id="supportedContentDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                      <div class="dropdown-menu" aria-labelledby="supportedContentDropdown3">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </li>
                  </ul>
                  <form class="form-inline float-lg-right">
                    <input class="form-control" type="text" placeholder="Search">
                    <button class="btn btn-primary" type="submit">Search</button>
                  </form>
                </div>
              </nav>
            </div>

            <div class="bs-component">
              <nav class="navbar navbar-dark bg-faded">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarResponsive1" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></button>
                <div class="container collapse navbar-toggleable-md" id="navbarResponsive1">
                  <a class="navbar-brand" href="#">Navbar</a>
                  <ul class="nav navbar-nav">
                    <li class="nav-item active">
                      <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="http://example.com" id="supportedContentDropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                      <div class="dropdown-menu" aria-labelledby="supportedContentDropdown1">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </li>
                  </ul>
                  <form class="form-inline float-lg-right">
                    <input class="form-control" type="text" placeholder="Search">
                    <button class="btn btn-primary" type="submit">Search</button>
                  </form>
                </div>
              </nav>
            </div>

          </div>
        </div>
      </div>


      <!-- Buttons
      ================================================== -->
      <div class="bs-docs-section">
        <div class="page-header">
          <div class="row">
            <div class="col-lg-12">
              <h1 id="buttons">Buttons</h1>
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
                  <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="#">Dropdown link</a>
                    <a class="dropdown-item" href="#">Dropdown link</a>
                  </div>
                </div>
              </div>

              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-success">Success</button>
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop2" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                    <a class="dropdown-item" href="#">Dropdown link</a>
                    <a class="dropdown-item" href="#">Dropdown link</a>
                  </div>
                </div>
              </div>
              
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-info">Info</button>
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop3" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
                    <a class="dropdown-item" href="#">Dropdown link</a>
                    <a class="dropdown-item" href="#">Dropdown link</a>
                  </div>
                </div>
              </div>
              
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-danger">Danger</button>
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop4" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop4">
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
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary active">
                  <input type="checkbox" checked> Checkbox 1
                </label>
                <label class="btn btn-primary">
                  <input type="checkbox"> Checkbox 2
                </label>
                <label class="btn btn-primary">
                  <input type="checkbox"> Checkbox 3
                </label>
              </div>
            </div>

            <div class="bs-component" style="margin-bottom: 15px;">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary active">
                  <input type="radio" name="options" id="option1" checked> Radio 1
                </label>
                <label class="btn btn-primary">
                  <input type="radio" name="options" id="option2"> Radio 2
                </label>
                <label class="btn btn-primary">
                  <input type="radio" name="options" id="option3"> Radio 3
                </label>
              </div>
            </div>

            <div class="bs-component">
              <div class="btn-group-vertical" data-toggle="buttons">
                <label class="btn btn-primary active">
                  <input type="radio" name="options" id="option4" checked> Radio 1
                </label>
                <label class="btn btn-primary">
                  <input type="radio" name="options" id="option5"> Radio 2
                </label>
                <label class="btn btn-primary">
                  <input type="radio" name="options" id="option6"> Radio 3
                </label>
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
                <div class="btn-group" role="group" aria-label="First group">
                  <button type="button" class="btn btn-secondary">1</button>
                  <button type="button" class="btn btn-secondary">2</button>
                  <button type="button" class="btn btn-secondary">3</button>
                  <button type="button" class="btn btn-secondary">4</button>
                </div>
                <div class="btn-group" role="group" aria-label="Second group">
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

      <!-- Typography
      ================================================== -->
      <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="typography">Typography</h1>
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
                <small class="text-muted">with muted text</small>
              </h3>
              <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            </div>
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
              <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
              <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
              <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
              <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
              <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
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
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote class="blockquote">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
              </blockquote>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote class="blockquote blockquote-reverse">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
              </blockquote>
            </div>
          </div>
        </div>
      </div>

      <!-- Tables
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="tables">Tables</h1>
            </div>

            <div class="bs-component">
              <table class="table table-striped table-hover table-bordered">
                <thead class="thead-inverse">
                  <tr>
                    <th>#</th>
                    <th>Column heading</th>
                    <th>Column heading</th>
                    <th>Column heading</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="table-info">
                    <td>3</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="table-success">
                    <td>4</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="table-danger">
                    <td>5</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="table-warning">
                    <td>6</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                  <tr class="table-active">
                    <td>7</td>
                    <td>Column content</td>
                    <td>Column content</td>
                    <td>Column content</td>
                  </tr>
                </tbody>
              </table> 
            </div><!-- /example -->
          </div>
        </div>
      </div>

      <!-- Forms
      ================================================== -->
      <div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="forms">Forms</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="bs-component">
              <form>
                <fieldset>
                  <legend>Legend</legend>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="exampleSelect1">Example select</label>
                    <select class="form-control" id="exampleSelect1">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelect2">Example multiple select</label>
                    <select multiple class="form-control" id="exampleSelect2">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleTextarea">Example textarea</label>
                    <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                  </div>
                  <fieldset class="form-group">
                    <legend>Radio buttons</legend>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                        Option one is this and that&mdash;be sure to include why it's great
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
                        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                        Option three is disabled
                      </label>
                    </div>
                  </fieldset>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input">
                      Check me out
                    </label>
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="col-lg-4 col-lg-offset-1">

              <form class="bs-component">

                <div class="form-group">
                  <fieldset disabled>
                    <label class="control-label" for="disabledInput">Disabled input</label>
                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
                  </fieldset>
                </div>

                <div class="form-group">
                  <fieldset>
                    <label class="control-label" for="readOnlyInput">Readonly input</label>
                    <input class="form-control" id="readOnlyInput" type="text" placeholder="Readonly input here…" readonly>
                  </fieldset>
                </div>

                <div class="form-group has-success">
                  <label class="form-control-label" for="inputSuccess1">Input with success</label>
                  <input type="text" class="form-control form-control-success" id="inputSuccess1">
                  <div class="form-control-feedback">Success! You've done it.</div>
                </div>

                <div class="form-group has-warning">
                  <label class="form-control-label" for="inputWarning1">Input with warning</label>
                  <input type="text" class="form-control form-control-warning" id="inputWarning1">
                  <div class="form-control-feedback">Shucks, try again.</div>
                </div>

                <div class="form-group has-danger">
                  <label class="form-control-label" for="inputDanger1">Input with danger</label>
                  <input type="text" class="form-control form-control-danger" id="inputDanger1">
                  <div class="form-control-feedback">Sorry, that username's taken. Try another?</div>
                </div>

                <div class="form-group">
                  <label class="col-form-label col-form-label-lg" for="inputLarge">Large input</label>
                  <input class="form-control form-control-lg" type="text" id="inputLarge">
                </div>

                <div class="form-group">
                  <label class="col-form-label" for="inputDefault">Default input</label>
                  <input type="text" class="form-control" id="inputDefault">
                </div>

                <div class="form-group">
                  <label class="col-form-label col-form-label-sm" for="inputSmall">Small input</label>
                  <input class="form-control form-control-sm" type="text" id="inputSmall">
                </div>

                <div class="form-group">
                  <label class="control-label">Input addons</label>
                  <div class="form-group">
                    <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                    <div class="input-group">
                      <div class="input-group-addon">$</div>
                      <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                      <div class="input-group-addon">.00</div>
                    </div>
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
                  <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#profile">Profile</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" href="#">Disabled</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
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
                <div class="tab-pane fade active in" id="home">
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
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
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
              <ul class="nav nav-pills nav-stacked">
                <li class="nav-item">
                  <a class="nav-link active" href="#">Active</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
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
            </div>
          </div>

          <div class="col-lg-6">
            <h2 id="pagination">Pagination</h2>
            <div class="bs-component">
              <div>
                <ul class="pagination">
                  <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
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
                    <a class="page-link" href="#">&raquo;</a>
                  </li>
                </ul>
              </div>

              <div>
                <ul class="pagination pagination-lg">
                  <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
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
                    <a class="page-link" href="#">&raquo;</a>
                  </li>
                </ul>
              </div>

              <div>
                <ul class="pagination pagination-sm">
                  <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
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
                    <a class="page-link" href="#">&raquo;</a>
                  </li>
                </ul>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- Indicators
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="indicators">Indicators</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <h2>Alerts</h2>
            <div class="bs-component">
              <div class="alert alert-dismissible alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Warning!</h4>
                <p>Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, <a href="#" class="alert-link">vel scelerisque nisl consectetur et</a>.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Well done!</strong> You successfully read <a href="#" class="alert-link">this important alert message</a>.
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="alert alert-dismissible alert-info">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Heads up!</strong> This <a href="#" class="alert-link">alert needs your attention</a>, but it's not super important.
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <h2>Tags</h2>
            <div class="bs-component" style="margin-bottom: 40px;">
              <span class="tag tag-default">Default</span>
              <span class="tag tag-primary">Primary</span>
              <span class="tag tag-success">Success</span>
              <span class="tag tag-warning">Warning</span>
              <span class="tag tag-danger">Danger</span>
              <span class="tag tag-info">Info</span>
            </div>
            <div class="bs-component">
              <span class="tag tag-pill tag-default">Default</span>
              <span class="tag tag-pill tag-primary">Primary</span>
              <span class="tag tag-pill tag-success">Success</span>
              <span class="tag tag-pill tag-warning">Warning</span>
              <span class="tag tag-pill tag-danger">Danger</span>
              <span class="tag tag-pill tag-info">Info</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Progress
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="progress">Progress</h1>
            </div>

            <h3 id="progress-basic">Basic</h3>
            <div class="bs-component">
              <progress class="progress" value="50" max="100" ></progress>
            </div>

            <h3 id="progress-alternatives">Contextual alternatives</h3>
            <div class="bs-component">
              <progress class="progress progress-success" value="25" max="100" ></progress>
              <progress class="progress progress-info" value="50" max="100" ></progress>
              <progress class="progress progress-warning" value="75" max="100" ></progress>
              <progress class="progress progress-danger" value="100" max="100" ></progress>
            </div>

            <h3 id="progress-striped">Striped</h3>
            <div class="bs-component">
              <progress class="progress progress-striped" value="10" max="100"></progress>
              <progress class="progress progress-striped progress-success" value="25" max="100"></progress>
              <progress class="progress progress-striped progress-info" value="50" max="100"></progress>
              <progress class="progress progress-striped progress-warning" value="75" max="100"></progress>
              <progress class="progress progress-striped progress-danger" value="100" max="100"></progress>
            </div>

            <h3 id="progress-animated">Animated</h3>
            <div class="bs-component">
              <progress class="progress progress-striped progress-animated" value="25" max="100"></progress>
            </div>
          </div>
        </div>
      </div>

      <!-- Containers
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="containers">Containers</h1>
            </div>
            <div class="bs-component">
              <div class="jumbotron">
                <h1 class="display-3">Jumbotron</h1>
                <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                <p><a class="btn btn-primary btn-lg" href="#">Learn more</a></p>
              </div>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-lg-12">
            <h2>List groups</h2>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <ul class="list-group">
                <li class="list-group-item">
                  <span class="tag tag-default tag-pill float-xs-right">14</span>
                  Cras justo odio
                </li>
                <li class="list-group-item">
                  <span class="tag tag-default tag-pill float-xs-right">2</span>
                  Dapibus ac facilisis in
                </li>
                <li class="list-group-item">
                  <span class="tag tag-default tag-pill float-xs-right">1</span>
                  Morbi leo risus
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="list-group">
                <a href="#" class="list-group-item  list-group-item-action active">
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
                <a href="#" class="list-group-item list-group-item-action active">
                  <h4 class="list-group-item-heading">List group item heading</h4>
                  <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                  <h4 class="list-group-item-heading">List group item heading</h4>
                  <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <h2>Cards</h2>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="card card-inverse card-primary text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-inverse card-success text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-inverse card-info text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-inverse card-warning text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-inverse card-danger text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="card card-outline-primary text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-outline-success text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-outline-info text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-outline-warning text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
              <div class="card card-outline-danger text-xs-center">
                <div class="card-block">
                  <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                  </blockquote>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="bs-component">
              <div class="card">
                <h3 class="card-header">Card header</h3>
                <div class="card-block">
                  <h5 class="card-title">Special title treatment</h5>
                  <h6 class="card-subtitle text-muted">Support card subtitle</h6>
                </div>
                <img style="height: 200px; width: 100%; display: block;" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22318%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20318%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_158bd1d28ef%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A16pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_158bd1d28ef%22%3E%3Crect%20width%3D%22318%22%20height%3D%22180%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22129.359375%22%20y%3D%2297.35%22%3EImage%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" alt="Card image">
                <div class="card-block">
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  <a href="#" class="card-link">Card link</a>
                  <a href="#" class="card-link">Another link</a>
                </div>
                <div class="card-footer text-muted text-xs-center">
                  2 days ago
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Dialogs
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="dialogs">Dialogs</h1>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <h2>Modals</h2>
            <div class="bs-component">
              <div class="modal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                      <p>One fine body…</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <h2>Popovers</h2>
            <div class="bs-component" style="margin-bottom: 3em;">
              <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Left</button>

              <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Top</button>

              <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
              sagittis lacus vel augue laoreet rutrum faucibus.">Bottom</button>

              <button type="button" class="btn btn-secondary" title="Popover Title" data-container="body" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Right</button>
            </div>
            <h2>Tooltips</h2>
            <div class="bs-component">
              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Left</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Top</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Bottom</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Right</button>
            </div>
          </div>
        </div>
      </div>

      <div id="source-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Source Code</h4>
            </div>
            <div class="modal-body">
              <pre></pre>
            </div>
          </div>
        </div>
      </div>

      <footer>
        <div class="row">
          <div class="col-sm-12">
            <ul class="list-unstyled">
              <li class="float-xs-right"><a href="#top">Back to top</a></li>
            </ul>
            <p>Made by <a href="http://thomaspark.co" rel="nofollow">Thomas Park</a>. Contact him at <a href="mailto:thomas@bootswatch.com">thomas@bootswatch.com</a>.</p>
            <p>Npds fork by <a href="#" rel="nofollow">Jpb</a>. Contact him at <a href="mailto:jpb@npds.org">jpb@npds.org</a>.</p>
            <p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/gh-pages/LICENSE">MIT License</a>.</p>
            <p>Based on <a href="http://getbootstrap.com" rel="nofollow">Bootstrap 4</a>. Icons from <a href="http://fortawesome.github.io/Font-Awesome/" rel="nofollow">Font Awesome</a>. Web fonts from <a href="http://www.google.com/webfonts" rel="nofollow">Google</a>.</p>
            <p>Used Color generators <a href="http://paintstrap.com/" rel="nofollow">Paintstrap</a> <a href="http://www.lavishbootstrap.com//" rel="nofollow">Lavish</a></p>
          </div>
        </div>
      </footer>
    </div>
      <script src="../../../../lib/js/jquery.min.js"></script>
      <script type="text/javascript" src="../../../../lib/js/tether.min.js"></script>
      <script src="../../../../lib/bootstrap/dist/js/bootstrap.min.js"></script>
      <script src="../assets/js/bootswatch.js"></script>
   </body>
</html>
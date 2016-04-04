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
      if ($cookie[9]=="") $cookie[9]=$Default_Theme;
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
      <meta charset="utf-8">
      <title>Bootswatch skins for NPDS - skin : <?php echo $skinametitre; ?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <link rel="stylesheet" href="../../../../lib/font-awesome-4.5.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="bootstrap.css" />
      <link rel="stylesheet" href="extra.css">
      <link rel="stylesheet" href="../assets/css/bootswatch.min.css" />
      <link rel="stylesheet" href="../../npds-boost_sk/style/style.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/html5shiv/dist/html5shiv.js"></script>
      <script src="../bower_components/respond/dest/respond.min.js"></script>
    <![endif]-->
   </head>
   <body>
   <nav id="uppernavbar" class="navbar navbar-fixed-top navbar-default navbar-dark bg-inverse ">
      <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#barnav">&#9776;</button>
      <a class="navbar-brand" href="#"><span>NPDS</span></a>
      <div class="collapse navbar-toggleable-xs" id="barnav">
         <ul class="nav navbar-nav">
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
         <ul class="nav navbar-nav pull-xs-right">
            <li class="nav-item"><a class="nav-link" href="http://bootswatch.com/" target="_blank">Built With Bootswatch</a></li>
         </ul>
      </div>
   </nav>
   <div class="">
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
   <div class="container-fluid">




      <!-- Navbar
      ================================================== -->
      <div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="navbar">Navbar</h1>
            </div>
            
            <div class="bs-component">
            <nav class="navbar navbar-light bg-faded">
              <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">&#9776;</button>
              <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
                <a class="navbar-brand" href="#">Responsive navbar</a>
                <ul class="nav navbar-nav">
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Themes</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Skins</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                  </li>
                </ul>
              </div>
            </nav>
            </div>
            <br />
            <div class="collapse" id="exCollapsingNavbar">
              <div class="bg-inverse p-a-1">
                <h4>Collapsed content</h4>
                <span class="text-muted">Toggleable via the navbar brand.</span>
              </div>
            </div>
            <nav class="navbar navbar-light bg-faded">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                &#9776;
              </button>
            </nav>
           <br />
            
<div class="bs-component">
  <nav class="navbar navbar-dark bg-inverse">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="nav navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Themes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Skins</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Help</a>
      </li>
    </ul>
    <form class="form-inline pull-xs-right">
      <input class="form-control" type="text" placeholder="Search">
      <button class="btn btn-info-outline" type="submit">Search</button>
    </form>
  </nav>
  <br />
  <nav class="navbar navbar-dark bg-primary">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="nav navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Theme</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Skins</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Help</a>
      </li>
    </ul>
    <form class="form-inline pull-xs-right">
      <input class="form-control" type="text" placeholder="Search">
      <button class="btn btn-secondary-outline" type="submit">Search</button>
    </form>
  </nav>
  <br />
  <nav class="navbar navbar-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="nav navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Themes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Skins</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Help</a>
      </li>
    </ul>
    <form class="form-inline pull-xs-right">
      <input class="form-control" type="text" placeholder="Search">
      <button class="btn btn-primary-outline" type="submit">Search</button>
    </form>
  </nav>
</div>








<!-- /example -->

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
          <div class="col-lg-6">

            <p class="bs-component">
              <a href="#" class="btn btn-secondary">Default</a>
              <a href="#" class="btn btn-primary">Primary</a>
              <a href="#" class="btn btn-success">Success</a>
              <a href="#" class="btn btn-info">Info</a>
              <a href="#" class="btn btn-warning">Warning</a>
              <a href="#" class="btn btn-danger">Danger</a>
              <a href="#" class="btn btn-link">Link</a>
            </p>

            <p class="bs-component">
              <a href="#" class="btn btn-secondary disabled">Default</a>
              <a href="#" class="btn btn-primary disabled">Primary</a>
              <a href="#" class="btn btn-success disabled">Success</a>
              <a href="#" class="btn btn-info disabled">Info</a>
              <a href="#" class="btn btn-warning disabled">Warning</a>
              <a href="#" class="btn btn-danger disabled">Danger</a>
              <a href="#" class="btn btn-link disabled">Link</a>
            </p>


            <div style="margin-bottom: 15px;">
              <div class="btn-toolbar bs-component" style="margin: 0;">
                <div class="btn-group">
                  <a href="#" class="btn btn-secondary">Default</a>
                  <a href="#" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-primary">Primary</a>
                  <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-success">Success</a>
                  <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-info">Info</a>
                  <a href="#" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-warning">Warning</a>
                  <a href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <p class="bs-component">
              <a href="#" class="btn btn-primary btn-lg">Large button</a>
              <a href="#" class="btn btn-primary">Default button</a>
              <a href="#" class="btn btn-primary btn-sm">Small button</a>
            </p>

          </div>
          <div class="col-lg-6">

            <p class="bs-component">
              <a href="#" class="btn btn-secondary btn-lg btn-block">Block level button</a>
            </p>


            <div class="bs-component" style="margin-bottom: 15px;">
              <div class="btn-group btn-group-justified">
                <a href="#" class="btn btn-secondary">Left</a>
                <a href="#" class="btn btn-secondary">Middle</a>
                <a href="#" class="btn btn-secondary">Right</a>
              </div>
            </div>

            <div class="bs-component" style="margin-bottom: 15px;">
              <div class="btn-toolbar">
                <div class="btn-group">
                  <a href="#" class="btn btn-secondary">1</a>
                  <a href="#" class="btn btn-secondary">2</a>
                  <a href="#" class="btn btn-secondary">3</a>
                  <a href="#" class="btn btn-secondary">4</a>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-secondary">5</a>
                  <a href="#" class="btn btn-secondary">6</a>
                  <a href="#" class="btn btn-secondary">7</a>
                </div>

                <div class="btn-group">
                  <a href="#" class="btn btn-secondary">8</a>
                  <div class="btn-group">
                    <a href="#" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                      Dropdown
                      <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a href="#">Dropdown link</a></li>
                      <li><a href="#">Dropdown link</a></li>
                      <li><a href="#">Dropdown link</a></li>
                     </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="bs-component">
              <div class="btn-group-vertical">
                  <a href="#" class="btn btn-secondary">Button</a>
                  <a href="#" class="btn btn-secondary">Button</a>
                  <a href="#" class="btn btn-secondary">Button</a>
                  <a href="#" class="btn btn-secondary">Button</a>
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
              <h1 id="type">Typography</h1>
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
              <p class="lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <h2>Example body text</h2>
              <p>Nullam quis risus eget <a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.</p>
              <p><small>This line of text is meant to be treated as fine print.</small></p>
              <p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
              <p>The following snippet of text is <em>rendered as italicized text</em>.</p>
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
              <blockquote>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
              </blockquote>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="bs-component">
              <blockquote class="pull-right">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
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
              <table class="table table-striped table-hover ">
                <thead>
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
            <div class="card card-block bs-component">
              <form class="form-horizontal">
                <fieldset>
                  <legend>Legend</legend>
                  <div class="form-group row">
                    <label for="inputEmail" class="col-lg-2 form-control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword" class="col-lg-2 form-control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> Checkbox
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="textArea" class="col-lg-2 form-control-label">Textarea</label>
                    <div class="col-lg-10">
                      <textarea class="form-control" rows="3" id="textArea"></textarea>
                      <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-2 form-control-label">Radios</label>
                    <div class="col-lg-10">
                      <div class="radio">
                        <label>
                          <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                          Option one is this
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                          Option two can be something else
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="select" class="col-lg-2 form-control-label">Selects</label>
                    <div class="col-lg-10">
                      <select class="form-control" id="select">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                      <br>
                      <select multiple="" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-lg-10 col-lg-offset-2">
                      <button type="reset" class="btn btn-secondary">Cancel</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="col-lg-4 col-lg-offset-1">

              <form class="bs-component">
                <div class="form-group row">
                  <label class="form-control-label" for="focusedInput">Focused input</label>
                  <input class="form-control" id="focusedInput" type="text" value="This is focused...">
                </div>

                <div class="form-group row">
                  <label class="form-control-label" for="disabledInput">Disabled input</label>
                  <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input here..." disabled="">
                </div>

                <div class="form-group row has-warning">
                  <label class="form-control-label" for="inputWarning">Input warning</label>
                  <input type="text" class="form-control" id="inputWarning">
                </div>

                <div class="form-group row has-danger">
                  <label class="form-control-label" for="inputError">Input error</label>
                  <input type="text" class="form-control" id="inputError">
                </div>

                <div class="form-group row has-success">
                  <label class="form-control-label" for="inputSuccess">Input success</label>
                  <input type="text" class="form-control" id="inputSuccess">
                </div>

                <div class="form-group row">
                  <label class="form-control-label" for="inputLarge">Large input</label>
                  <input class="form-control input-lg" type="text" id="inputLarge">
                </div>

                <div class="form-group row">
                  <label class="form-control-label" for="inputDefault">Default input</label>
                  <input type="text" class="form-control" id="inputDefault">
                </div>

                <div class="form-group row">
                  <label class="form-control-label" for="inputSmall">Small input</label>
                  <input class="form-control input-sm" type="text" id="inputSmall">
                </div>

                <div class="form-group row">
                  <label class="form-control-label">Input addons</label>
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button class="btn btn-secondary" type="button">Button</button>
                    </span>
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
              <h1 id="nav">Navs</h1>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <h2 id="nav-tabs">Tabs</h2>
            <div class="bs-component">
              <ul class="nav nav-tabs">
                <li class="nav-item" ><a class="nav-link active" href="#home" data-toggle="tab">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab">Profile</a></li>
                <li class="nav-item" ><a class="nav-link disabled">Disabled</a></li>
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
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item" ><a class="nav-link" href="#">Profile</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Disabled</a></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Dropdown</a>
                  <ul class="dropdown-menu">
                    <li class="nav-item"><a class="nav-link" href="#">Action</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Another action</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Something else here</a></li>
                    <li class="nav-item divider"></li>
                    <li class="nav-item"><a class="nav-link"  href="#">Separated link</a></li>
                  </ul>
                </li>
              </ul>
            </div>
            <br>
            <div class="bs-component">
              <ul class="nav nav-pills nav-stacked">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="#">Disabled</a></li>
<!-- 
                <li class="nav-item dropdown">
                  <a class="nav-item dropdown-toggle" data-toggle="dropdown" href="#">Dropdown</a>
                  <ul class="nav-item dropdown-menu">
                    <li class="nav-item"><a class="nav-link" href="#">Action</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Another action</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Something else here</a></li>
                    <li class="nav-item divider"></li>
                    <li class="nav-item"><a class="nav-link"href="#">Separated link</a></li>
                  </ul>
                </li>
 -->
              </ul>
            </div>
          </div>
        </div>
        
        <div class="row">
        <div class="col-lg-12">
            <h2 id="nav-breadcrumbs">Breadcrumbs</h2>
            <div class="bs-component">
              <ul class="breadcrumb">
                <li class="active">Home</li>
              </ul>

              <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Library</li>
              </ul>

              <ul class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Library</a></li>
                <li class="active">Data</li>
              </ul>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <h2 id="pagination">Pagination</h2>
            <div class="bs-component">
              <ul class="pagination">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item" ><a class="page-link" href="#">2</a></li>
                <li class="page-item" ><a class="page-link" href="#">3</a></li>
                <li class="page-item" ><a class="page-link" href="#">4</a></li>
                <li class="page-item" ><a class="page-link" href="#">5</a></li>
                <li class="page-item" ><a class="page-link" href="#">&raquo;</a></li>
              </ul>
              <ul class="pagination pagination-lg">
                <li class="page-item  disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item" ><a class="page-link" href="#">2</a></li>
                <li class="page-item" ><a class="page-link" href="#">3</a></li>
                <li class="page-item" ><a class="page-link" href="#">&raquo;</a></li>
              </ul>
              <ul class="pagination pagination-sm">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item" ><a class="page-link" href="#">2</a></li>
                <li class="page-item" ><a class="page-link" href="#">3</a></li>
                <li class="page-item" ><a class="page-link" href="#">4</a></li>
                <li class="page-item" ><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-6">
            <h2 id="pager">Pager</h2>
            <div class="bs-component">
              <ul class="pager">
                <li><a href="#">Previous</a></li>
                <li><a href="#">Next</a></li>
              </ul>

              <ul class="pager">
                <li class="previous disabled"><a href="#">&larr; Older</a></li>
                <li class="next"><a href="#">Newer &rarr;</a></li>
              </ul>
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
          <div class="col-lg-4">
            <h2>Labels</h2>
            <div class="bs-component" style="margin-bottom: 40px;">
              <span class="label label-default">Default</span>
              <span class="label label-primary">Primary</span>
              <span class="label label-success">Success</span>
              <span class="label label-warning">Warning</span>
              <span class="label label-danger">Danger</span>
              <span class="label label-info">Info</span>
            </div>
          </div>
          <div class="col-lg-4">
            <h2>Badges</h2>
            <div class="bs-component">
              <ul class="nav nav-pills">
                <li class="active"><a href="#">Home <span class="badge">42</span></a></li>
                <li><a href="#">Profile <span class="badge"></span></a></li>
                <li><a href="#">Messages <span class="badge">3</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Progress bars
      ================================================== -->
      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="progress">Progress bars</h1>
            </div>

            <h3 id="progress-basic">Basic</h3>
            <div class="bs-component">
                <progress class="progress" value="60" max="100"></progress>
            </div>

            <h3 id="progress-alternatives">Contextual alternatives</h3>
            <div class="bs-component">
                <progress class="progress progress-info" value="20" max="100">20%</progress>
                <progress class="progress progress-success" value="40" max="100"></progress>
                <progress class="progress progress-warning" value="60" max="100"></progress>
                <progress class="progress progress-danger" value="80" max="100"></progress>
            </div>
            <h3 id="progress-striped">Striped</h3>
            <div class="bs-component">
               <progress class="progress progress-striped" value="10" max="100">10%</progress>
               <progress class="progress progress-striped progress-success" value="25" max="100">25%</progress>
               <progress class="progress progress-striped progress-info" value="50" max="100">50%</progress>
               <progress class="progress progress-striped progress-warning" value="75" max="100">75%</progress>
               <progress class="progress progress-striped progress-danger" value="100" max="100">100%</progress>
            </div>

            <h3 id="progress-animated">Animated</h3>
            <div class="bs-component">
               <progress class="progress progress-striped progress-animated" value="25" max="100">35%</progress>
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
              <h1 id="container">Containers</h1>
            </div>
            <div class="bs-component">
              <div class="jumbotron">
                <h1>Jumbotron</h1>
                <p>This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
                <p><a class="btn btn-primary btn-lg">Learn more</a></p>
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
                  <span class="pull-xs-right">14</span>
                  Cras justo odio
                </li>
                <li class="list-group-item">
                  <span class="pull-xs-right">2</span>
                  Dapibus ac facilisis in
                </li>
                <li class="list-group-item">
                  <span class="pull-xs-right">1</span>
                  Morbi leo risus
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="list-group">
                <a href="#" class="list-group-item active">
                  Cras justo odio
                </a>
                <a href="#" class="list-group-item">Dapibus ac facilisis in
                </a>
                <a href="#" class="list-group-item">Morbi leo risus
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="list-group">
                <a href="#" class="list-group-item">
                  <h4 class="list-group-item-heading">List group item heading</h4>
                  <p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                </a>
                <a href="#" class="list-group-item">
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
              <div class="card ">
                <div class="card-block">
                  Basic card
                </div>
              </div>

              <div class="card">
                <div class="card-header">Card heading</div>
                <div class="card-block">
                  Card content
                </div>
              </div>

              <div class="card">
                <div class="card-block">
                  Card content
                </div>
                <div class="card-footer">Card footer</div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="card card-inverse card-primary">
                <div class="card-header">
                  <h3 class="card-title">Card primary</h3>
                </div>
                <div class="card-block">
                  Card content
                </div>
              </div>

              <div class="card card-inverse card-success">
                <div class="card-header">
                  <h3 class="card-title">Card success</h3>
                </div>
                <div class="card-block">
                  Card content
                </div>
              </div>

              <div class="card card-inverse card-warning">
                <div class="card-header">
                  <h3 class="card-title">Card warning</h3>
                </div>
                <div class="card-block">
                  Card content
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="bs-component">
              <div class="card card-inverse card-danger">
                <div class="card-header">
                  <h3 class="card-title">Card danger</h3>
                </div>
                <div class="card-block">
                  Card content
                </div>
              </div>

              <div class="card card-inverse card-info">
                <div class="card-header">
                  <h3 class="card-title">Card info</h3>
                </div>
                <div class="card-block">
                  Card content
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
            <div class="bs-component">
              <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Left</button>

              <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Top</button>

              <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
              sagittis lacus vel augue laoreet rutrum faucibus.">Bottom</button>

              <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Right</button>
            </div>
            <h2>Tooltips</h2>
            <div class="bs-component">
              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="left" title="" data-original-title="Tooltip on left">Left</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top">Top</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Tooltip on bottom">Bottom</button>

              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="" data-original-title="Tooltip on right">Right</button>
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
              <li class="pull-xs-right"><a href="#top">Back to top</a></li>
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
    <script src="../../../../lib/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="../../../../lib/js/tether.min.js"></script>
    <script src="../../../../lib/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootswatch.js"></script>
  </body>
</html>
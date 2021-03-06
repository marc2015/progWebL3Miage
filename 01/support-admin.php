<?php
 include("config.php");
global $dbh;
    $admins=$dbh->query("SELECT * FROM coc_client, coc_support WHERE COC_CLIENT_id=idexp AND typeexp='CLIENT'  GROUP BY COC_CLIENT_id"); 
    //$thisadmin=$admins->fetch(); 
    $thisadmin=$dbh->query("SELECT * FROM coc_admin WHERE COC_USER_id=".$_SESSION['user']['COC_USER_id'])->fetch();                   
    $idAdmin=$thisadmin['COC_ADMIN_id'];
    if (isset($_GET["idClient"])) { 
        $_SESSION["idClient"]=$_GET["idClient"];
         header("location:support-admin.php"); 
    }
   // $idAdmin=$thisadmin['COC_ADMIN_id'];
    $idexp=$idAdmin; 
    $iddest=$_SESSION["idClient"];  
    $typeexp=$thisadmin['COC_ADMIN_correspondant'];  
    //echo $_SESSION["idClient"];
    if(isset($_SESSION["idClient"])){
        $thisClient=$dbh->query("SELECT * FROM coc_client WHERE COC_CLIENT_id=".$_SESSION["idClient"])->fetch();    
          $status=$dbh->query("SELECT * FROM coc_user WHERE COC_USER_id=".$thisClient["COC_USER_id"])->fetch(); 
            $typedesti=$status["COC_USER_status"]; 


        $tabsupports = array();

        function getMessages(){ 
            global $dbh; 
            global $idexp; 
            global $iddest; 
            global $typeexp; 
          // 1. On requête la base de données pour sortir les 20 derniers messagesORDER BY date DESC
          $resultats = $dbh->query("SELECT * FROM coc_support WHERE idexp='$idexp' and iddest='$iddest'   ORDER BY date DESC LIMIT 10 ");
          return $resultats; 
          //print_r($resultats);
        }
        $req=getMessages();
        foreach  ($req as $row) {
            $tabsupports[] = $row;
        }
        $supports=array_reverse($tabsupports); 
        //print_r($supports);
    }

    if ($_POST["messages"]) {  
            $insertion = $dbh->prepare("INSERT INTO coc_support SET 
                  idexp=:idexp,
                  iddest=:iddest,
                  typeexp=:typeexp,
                  messages=:messages,
                  typedesti=:typedesti");

            $insertion->execute(array(
                "idexp" => $idexp, 
                "iddest" => $iddest,
                "typeexp" => $typeexp,
                "typedesti" => $typedesti,
                "messages" => $_POST["messages"]
            )); 
        
    }
    
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ma Messagerie Par Partenaire Center</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">

    <link href="./main.8d288f825d8dffbbe55e.css" rel="stylesheet"></head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<body>
<script type="text/javascript">
    function select() { 
    var elt=document.getElementById('idClient');
    var client=elt.value; 
          if(client != ""){ 
             $.ajax({
                  url : "support-admin.php?idClient="+ client, // on donne l'URL du fichier de traitement
                  type : "POST", // la requête est de type POST
                  data : "idClient=" + client,// et on envoie nos données 
              }); 
         
          }

        //alert(client);

    }


    function postMessages(){
          var eltMessages=document.getElementById('messages'); 
          var messages=eltMessages.value; 
          if(messages != ""){ 
             $.ajax({
                  url : "support-admin.php", // on donne l'URL du fichier de traitement
                  type : "POST", // la requête est de type POST
                  data : "messages=" + messages,// et on envoie nos données 
              }); 
         
          }
          eltMessages.value='';
          eltMessages.focus();
          window.location.reload()

     }  
</script>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     
          <?php include("topheader.php"); ?>
    
   <div class="app-main">
           
                
                       <?php
                       include("menu.php");
                       ?>
                
                
                
                
                
           
            
            
               <div class="app-main__outer">
                <div class="app-main__inner p-0">
                    <div class="app-inner-layout chat-layout">
                        <div class="app-inner-layout__header text-white bg-premium-dark">
                            <div class="app-page-title">
                                <div class="page-title-wrapper">
                                    <div class="page-title-heading">
                                        <div class="page-title-icon"><i class="pe-7s-umbrella icon-gradient bg-sunny-morning"></i></div>
                                        <div>
                                            Votre support Partenaire CENTER
                                            <div class="page-title-subheading">Vos différents échanges</div>
                                        </div>
                                    </div>
                                    <div class="page-title-actions">
                                        <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Example Tooltip">
                                            <i class="fa fa-star"></i>
                                        </button>
                                        <div class="d-inline-block dropdown">
                                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                                        <i class="fa fa-business-time fa-w-20"></i>
                                                                    </span>
                                                Buttons
                                            </button>
                                            <!--<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-inbox"></i>
                                                            <span>
                                                                                    Inbox
                                                                                </span>
                                                            <div class="ml-auto badge badge-pill badge-secondary">86</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-book"></i>
                                                            <span>
                                                                                    Book
                                                                                </span>
                                                            <div class="ml-auto badge badge-pill badge-danger">5</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-picture"></i>
                                                            <span>
                                                                                    Picture
                                                                                </span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a disabled="" href="javascript:void(0);" class="nav-link disabled">
                                                            <i class="nav-link-icon lnr-file-empty"></i>
                                                            <span>
                                                                                    File Disabled
                                                                                </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__content card">
                                <div class="table-responsive">
                                    <div class="app-inner-layout__top-pane">
                                        <div class="pane-left">
                                            <div class="mobile-app-menu-btn">
                                                <button type="button" class="hamburger hamburger--elastic">
                                            <span class="hamburger-box">
                                                <span class="hamburger-inner"></span>
                                            </span>
                                                </button>
                                            </div>
                                            <div class="avatar-icon-wrapper mr-2">
                                               <!--  <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
 -->
                                                 <?php 
                                                     if($status["COC_USER_status"]==1){ ?>
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                    <?php } ?>


                                                <div class="avatar-icon avatar-icon-xl rounded"><img width="82" src="assets/images/avatars/<?php echo $thisClient['COC_CLIENT_file'] ?>"  alt=""></div>
                                            </div>
                                            <h4 class="mb-0 text-nowrap">
                                                <?php 
                                                    if(isset($_SESSION["idClient"])){
                                                        echo $thisClient["COC_CLIENT_prenom"]." ".$thisClient["COC_CLIENT_nom"];
                                                } ?> 
                                            </h4>
                                        </div>
                                        <div class="pane-right">
                                            <div class="btn-group dropdown">
                                               
                                            <span class="opacity-7 mr-1">
                                                <i class="fa fa-cog"></i>
                                            </span>
                                                   A venir
                                                </button>
                                              <!--  <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item-header nav-item">Activity</li>
                                                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link">Chat
                                                            <div class="ml-auto badge badge-pill badge-info">8</div>
                                                        </a></li>
                                                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link">Recover Password</a></li>
                                                        <li class="nav-item-header nav-item">My Account</li>
                                                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link">Settings
                                                            <div class="ml-auto badge badge-success">New</div>
                                                        </a></li>
                                                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link">Messages
                                                            <div class="ml-auto badge badge-warning">512</div>
                                                        </a></li>
                                                        <li class="nav-item"><a href="javascript:void(0);" class="nav-link">Logs</a></li>
                                                        <li class="nav-item-divider nav-item"></li>
                                                        <li class="nav-item-btn nav-item">
                                                            <button class="btn-wide btn-shadow btn btn-danger btn-sm">Cancel</button>
                                                        </li>
                                                    </ul>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div> 
                                   <div class="chat-wrapper">
                                         <?php   
                                            //print_r($supports);
                                         if ($supports) { 
                                            foreach ($supports as  $value) { 
                                                    $messages=$value["messages"]; 
                                                    $messages=addSpace($messages,130);
                                                if($value["typeexp"]!=$thisadmin['COC_ADMIN_correspondant']){
                                         ?>

                                             <div class="chat-box-wrapper" >
                                                <div>
                                                    <div class="avatar-icon-wrapper mr-1">
                                                        <?php 
                                                     if($status["COC_USER_status"]==1){ ?>
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                    <?php } ?>
                                                        <div class="avatar-icon avatar-icon-lg rounded">
                                                            <img
                                                                    src="assets/images/avatars/<?php echo $thisadmin['COC_ADMIN_file'] ?>"
                                                                    alt=""></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="chat-box"><?php echo $messages ?></div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        <?php echo date_format(date_create($value['date']), 'Y-m-d H:i:s'); ?>
                                                    </small>
                                                </div>
                                            </div>

                                        <?php }else{ ?>
                                        <div class="float-right">
                                            <div class="chat-box-wrapper chat-box-wrapper-right">
                                                <div>
                                                    <div class="chat-box"><?php echo $messages ?></div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        <?php echo date_format(date_create($value['date']), 'Y-m-d H:i:s'); ?>
                                                    </small>
                                                </div>
                                                <div>
                                                    <div class="avatar-icon-wrapper ml-1">
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                        <div class="avatar-icon avatar-icon-lg rounded"><img
                                                                src="assets/images/avatars/<?php echo $profile?>"
                                                                alt=""></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } }
                                         } ?>
                                    </div>

                                    <div class="app-inner-layout__bottom-pane d-block text-center">
                                        <div class="mb-0 position-relative row form-group">
                                            <div class="col-lg-12"> 
                                               <!--  <form action="" method="POST">  
                                                    <input  placeholder="Écrivez ici et appuyez sur Entrée pour envoyer..." type="text" name="messages" class="form-control-lg form-control" > 
                                                    <button type="submit" value="Envoyer" class="form-control-lg form-control">Envoyer</button>
                                                </form> -->

                                                 <input  placeholder="Écrivez ici et appuyez sur éntrée pour envoyer..." type="text" id="messages" class="form-control-lg form-control" onkeydown="if (event.keyCode == 13) { postMessages(); return false; }">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="app-inner-layout__sidebar card">
                                <div class="app-inner-layout__sidebar-header">
                                    <ul class="nav flex-column">
                                        <li class="pt-4 pl-3 pr-3 pb-3 nav-item">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-search"></i>
                                                    </div>
                                                </div>
                                                <input placeholder="Recherche..." type="text" class="form-control"></div>
                                        </li>
                                        <li class="nav-item-header nav-item">Mes interlocuteurs</li>
                                    </ul>
                                </div>
                                <ul class="nav flex-column">

                                    <?php  
                                        foreach ($admins as $value) { 
                                            $idClient=$value['COC_CLIENT_id'];
                                        $messagesClient=$dbh->query("SELECT messages FROM coc_support WHERE idexp='$idClient'  AND typeexp='CLIENT' AND iddest='$idAdmin' ORDER BY date DESC LIMIT 0, 1")->fetch();

                                     ?>
                                    <li class="nav-item">
                                        <button type="button" tabindex="0" class="dropdown-item" onclick="document.location.href='support-admin.php?idClient=<?php echo $idClient?>'">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        <div class="avatar-icon-wrapper">
                                                            <div class="badge badge-bottom badge-success badge-dot badge-dot-lg"></div>
                                                            <div class="avatar-icon"><img
                                                                    src="assets/images/avatars/<?php echo $value['COC_CLIENT_file'] ?>"
                                                                    alt=""></div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="idClient" value="<?php echo $idClient ?>">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">
                                                            <?php echo $value["COC_CLIENT_prenom"]." ".$value["COC_CLIENT_nom"] ?></div>
                                                        <div class="widget-subheading">
                                                            <?php echo substr($messagesClient["messages"], 0, 40); ?>...
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </li>


                                    <?php    } ?>
                                   <!--  <li class="nav-item">
                                        <button type="button" tabindex="0" class="dropdown-item active">
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">
                                                        <div class="avatar-icon-wrapper">
                                                            <div class="badge badge-bottom badge-success badge-dot badge-dot-lg"></div>
                                                            <div class="avatar-icon"><img
                                                                    src="assets/images/avatars/contact1.jpg"
                                                                    alt=""></div>
                                                        </div>
                                                    </div>
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Amanda</div>
                                                        <div class="widget-subheading">Bonjour, Votre dernière facture</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    </li> -->
                                   
                                   
                                 
                           
                           
                           
                                </ul>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <div class="footer-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm badge-danger">Notifications</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header mb-0">
                                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                                    <div class="menu-header-content text-dark">
                                                        <h5 class="menu-header-title">Notifications</h5>
                                                        <h6 class="menu-header-subtitle">You have <b>21</b> unread messages</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-messages-header1">
                                                        <span>Messages</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-events-header1">
                                                        <span>Events</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-errors-header1">
                                                        <span>System Errors</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab-messages-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="p-3">
                                                                <div class="notifications-box">
                                                                    <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4><span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Build the production release
                                                                                        <span class="badge badge-danger ml-2">NEW</span>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-primary vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Something not important
                                                                                        <div class="avatar-wrapper mt-2 avatar-wrapper-overlap">
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/1.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/2.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/3.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/4.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/5.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/9.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/7.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon"><img
                                                                                                        src="assets/images/avatars/8.jpg"
                                                                                                        alt=""></div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm avatar-icon-add">
                                                                                                <div class="avatar-icon"><i>+</i></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-info vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">This dot has an info state</h4><span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4><span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Build the production release
                                                                                        <span class="badge badge-danger ml-2">NEW</span>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-dark vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">This dot has a dark state</h4><span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-events-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="p-3">
                                                                <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4>
                                                                                <p>Lorem ipsum dolor sic amet, today at <a href="javascript:void(0);">12:00 PM</a></p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-warning"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><p>Another meeting today, at <b class="text-danger">12:00 PM</b></p>
                                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">Build the production release</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor incididunt ut labore et dolore magna elit enim at minim veniam quis nostrud</p><span
                                                                                        class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title text-success">Something not important</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur elit enim at minim veniam quis nostrud</p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4>
                                                                                <p>Lorem ipsum dolor sic amet, today at <a href="javascript:void(0);">12:00 PM</a></p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-warning"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><p>Another meeting today, at <b class="text-danger">12:00 PM</b></p>
                                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">Build the production release</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor incididunt ut labore et dolore magna elit enim at minim veniam quis nostrud</p><span
                                                                                        class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title text-success">Something not important</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur elit enim at minim veniam quis nostrud</p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-errors-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="no-results pt-3 pb-0">
                                                                <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                                    <span class="swal2-success-line-tip"></span>
                                                                    <span class="swal2-success-line-long"></span>
                                                                    <div class="swal2-success-ring"></div>
                                                                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                                </div>
                                                                <div class="results-subtitle">All caught up!</div>
                                                                <div class="results-title">There are no system errors!</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="nav flex-column">
                                                <li class="nav-item-divider nav-item"></li>
                                                <li class="nav-item-btn text-center nav-item">
                                                    <button class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">View Latest Changes</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="dots-separator"></div>
                                    <div class="dropdown">
                                        <a class="dot-btn-wrapper" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                            <i class="dot-btn-icon lnr-earth icon-gradient bg-happy-itmeo">
                                            </i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner pt-4 pb-4 bg-focus">
                                                    <div class="menu-header-image opacity-05" style="background-image: url('assets/images/dropdown-header/city2.jpg');"></div>
                                                    <div class="menu-header-content text-center text-white">
                                                        <h6 class="menu-header-subtitle mt-0">
                                                            Choose Language
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <h6 tabindex="-1" class="dropdown-header">
                                                Popular Languages
                                            </h6>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="mr-3 opacity-8 flag large US"></span>
                                                USA
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="mr-3 opacity-8 flag large CH"></span>
                                                Switzerland
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="mr-3 opacity-8 flag large FR"></span>
                                                France
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="mr-3 opacity-8 flag large ES"></span>
                                                Spain
                                            </button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <h6 tabindex="-1" class="dropdown-header">Others</h6>
                                            <button type="button" tabindex="0" class="dropdown-item active">
                                                <span class="mr-3 opacity-8 flag large DE"></span>
                                                Germany
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="mr-3 opacity-8 flag large IT"></span>
                                                Italy
                                            </button>
                                        </div>
                                    </div>
                                    <div class="dots-separator"></div>
                                    <div class="dropdown">
                                        <a class="dot-btn-wrapper dd-chart-btn-2" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                                            <i class="dot-btn-icon lnr-pie-chart icon-gradient bg-love-kiss"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm badge-warning">Notifications</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner bg-premium-dark">
                                                    <div class="menu-header-image" style="background-image: url('assets/images/dropdown-header/abstract4.jpg');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Users Online
                                                        </h5>
                                                        <h6 class="menu-header-subtitle">Recent Account Activity Overview
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-chart">
                                                <div class="widget-chart-content">
                                                    <div class="icon-wrapper rounded-circle">
                                                        <div class="icon-wrapper-bg opacity-9 bg-focus">
                                                        </div>
                                                        <i class="lnr-users text-white">
                                                        </i>
                                                    </div>
                                                    <div class="widget-numbers">
                                                        <span>344k</span>
                                                    </div>
                                                    <div class="widget-subheading pt-2">
                                                        Profile views since last login
                                                    </div>
                                                    <div class="widget-description text-danger">
                                                        <span class="pr-1">
                                                            <span>176%</span>
                                                        </span>
                                                        <i class="fa fa-arrow-left"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-chart-wrapper">
                                                    <div id="dashboard-sparkline-carousel-4-pop"></div>
                                                </div>
                                            </div>
                                            <ul class="nav flex-column">
                                                <li class="nav-item-divider mt-0 nav-item">
                                                </li>
                                                <li class="nav-item-btn text-center nav-item">
                                                    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                                        <i class="fa fa-cog fa-spin mr-2">
                                                        </i>
                                                        View Details
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                
                                </div>
                            </div>
                            <div class="app-footer-right">
                                <ul class="header-megamenu nav">
                                    <li class="nav-item">
                                        <a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom" class="nav-link">
                                            Footer Menu
                                            <i class="fa fa-angle-up ml-2 opacity-8"></i>
                                        </a>
                                        <div class="rm-max-width rm-pointers">
                                            <div class="d-none popover-custom-content">
                                                <div class="dropdown-mega-menu dropdown-mega-menu-sm">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="no-gutters row">
                                                            <div class="col-sm-6 col-xl-6">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item-header nav-item">Overview</li>
                                                                    <li class="nav-item"><a class="nav-link"><i class="nav-link-icon lnr-inbox"> </i><span>Contacts</span></a></li>
                                                                    <li class="nav-item"><a class="nav-link"><i class="nav-link-icon lnr-book"> </i><span>Incidents</span>
                                                                        <div class="ml-auto badge badge-pill badge-danger">5</div>
                                                                    </a></li>
                                                                    <li class="nav-item"><a class="nav-link"><i class="nav-link-icon lnr-picture"> </i><span>Companies</span></a></li>
                                                                    <li class="nav-item"><a disabled="" class="nav-link disabled"><i class="nav-link-icon lnr-file-empty"> </i><span>Dashboards</span></a></li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-sm-6 col-xl-6">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item-header nav-item">Sales &amp; Marketing</li>
                                                                    <li class="nav-item"><a class="nav-link">Queues</a></li>
                                                                    <li class="nav-item"><a class="nav-link">Resource Groups</a></li>
                                                                    <li class="nav-item"><a class="nav-link">Goal Metrics
                                                                        <div class="ml-auto badge badge-warning">3</div>
                                                                    </a></li>
                                                                    <li class="nav-item"><a class="nav-link">Campaigns</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom" class="nav-link">
                                            Grid Menu
                                            <div class="badge badge-dark ml-0 ml-1">
                                                <small>NEW</small>
                                            </div>
                                            <i class="fa fa-angle-up ml-2 opacity-8"></i>
                                        </a>
                                        <div class="rm-max-width rm-pointers">
                                            <div class="d-none popover-custom-content">
                                                <div class="dropdown-menu-header">
                                                    <div class="dropdown-menu-header-inner bg-tempting-azure">
                                                        <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city5.jpg');"></div>
                                                        <div class="menu-header-content text-dark"><h5 class="menu-header-title">Two Column Grid</h5><h6 class="menu-header-subtitle">Easy grid navigation inside popovers</h6></div>
                                                    </div>
                                                </div>
                                                <div class="grid-menu grid-menu-2col">
                                                    <div class="no-gutters row">
                                                        <div class="col-sm-6">
                                                            <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark"><i class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2"> </i>Automation
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"><i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2"> </i>Reports
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success"><i class="lnr-bus text-success opacity-7 btn-icon-wrapper mb-2"> </i>Activity
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-focus"><i class="lnr-gift text-focus opacity-7 btn-icon-wrapper mb-2"> </i>Settings
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-divider nav-item"></li>
                                                    <li class="nav-item-btn clearfix nav-item">
                                                        <div class="float-left">
                                                            <button class="btn btn-link btn-sm">Link Button</button>
                                                        </div>
                                                        <div class="float-right">
                                                            <button class="btn-shadow btn btn-info btn-sm">Info Button</button>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>    </div>
    </div>
</div>

<?php
include("serveurstatus.php");
?>
<div class="app-drawer-overlay d-none animated fadeIn"></div><script type="text/javascript" src="./assets/scripts/main.8d288f825d8dffbbe55e.js"></script></body>
</html>

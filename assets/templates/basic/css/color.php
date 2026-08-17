<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here
$secondColor = "#ff8"; // Change your Color Here

function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) and $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor or !checkhexcolor2($secondColor)) {
    $secondColor = "#336699";
}
?>


.overview-item::after {
background: <?php echo $color; ?>;
background: linear-gradient(to top, <?php echo $color; ?>26, <?php echo $color; ?>73, <?php echo $color; ?>26);
}

.btn--base{
background: <?php echo $color; ?>;
}


.text--base, .custom--nav-tabs .nav-item .nav-link, .custom--accordion-two .accordion-button:not(.collapsed), .custom--field i, .overview-card__icon, .inline-menu li a:hover, .contact-info p a:hover, .info-card__iconm, .package-card__features li::before, .ratings i, .scroll-to-top .scroll-icon, .page-breadcrumb li:first-child::before, .contact-info i, .dashboard-card .number, .dashboard-card .icon, .header .main-menu li .sub-menu li a:hover, .header .main-menu li a:hover, .header .main-menu li a:focus, .header .main-menu li.menu_has_children > a::before, .header .main-menu li a.active, .header .main-menu li.menu_has_children:hover > a::before, .header .main-menu li a:hover, .header .main-menu li a:focus, .info-card__icon, .package-card__features li::before, a.text-white:hover {
color: <?php echo $color; ?> !important;
}

.btn--base:hover, .scroll-to-top, .preloader .preloader-container .animated-preloader, .footer, .header, .list-group-item {
background-color: <?php echo $secondColor; ?>;
}

.package-card, .custom--nav-tabs .nav-item .nav-link, .custom--nav-tabs .nav-item .nav-link.active, .custom--accordion .accordion-item, .testimonial-item, .contact-info-card:hover, .form--control, .modal .modal-content, .custom--card, .testimonial-item, .feature-card:hover, .form--control:focus, .member-card, .pagination .page-item .page-link:hover{
border-color: <?php echo $color; ?> !important;
}

.package-card__title, .how-work-card__step::before, .custom--nav-tabs .nav-item .nav-link.active, .custom--table thead, .inline-social-links li a:hover, .button-nav-tabs .nav-item .nav-link.active, .custom--accordion .accordion-button:not(.collapsed), .preloader .preloader-container .animated-preloader, .balance-card, .bg--base, .how-work-card__step::before, .counter-item::after, .pagination .page-item.active .page-link, .pagination .page-item .page-link:hover {
background-color: <?php echo $color; ?> !important;
}

.input-group-text{
border: none;
}


.feature-card, .overview-wrapper, .info-card__icon, .package-card, .how-work-card__step, .subscribe-wrapper, .member-card, .modal .modal-content, .contact-info i{
box-shadow: inset 0 0 10px <?php echo $color; ?>d9 !important;
}



.btn--base:hover{
background-color: <?php echo $color; ?> !important;
}

.cookies-card, .modal .modal-content, .mobile-code {
border: 2px solid <?php echo $color; ?> !important;
}
.form--control {
border: 1px solid <?php echo $color; ?> !important;
}

.pagination .page-item .page-link:hover {
border-color: <?php echo $color; ?> !important;
}

.text-shadow--base{
text-shadow: 0 0 5px <?php echo $color; ?> !important;
}


.list-group-item, .pagination .page-item .page-link {
border: 1px solid <?php echo $color; ?> !important;
}

.card-header {
border-bottom: .5px solid <?php echo $color; ?> !important;
}


a:hover,.page-breadcrumb li a:hover {
color: <?php echo $color; ?>;
}



.select2-search--dropdown {

  border-color: <?php echo $color; ?>!important;
}


.select2-container--default .select2-search--dropdown .select2-search__field {
  border-color: <?php echo $color; ?>!important;

}

.select2-results__option.select2-results__option--selected,
.select2-results__option--selectable,
.select2-container--default .select2-results__option--disabled {

  border-bottom: 1px solid <?php echo $color; ?>!important;
}

.select2-container--default .select2-results>.select2-results__options::-webkit-scrollbar-track {
  background:<?php echo $color; ?>!important;
  border-radius: 5px;
}

.select2-container--default .select2-results>.select2-results__options::-webkit-scrollbar-thumb {
  background: <?php echo $color; ?>!important;
}

.select2-container--default .select2-results>.select2-results__options::-webkit-scrollbar-thumb:hover {
  background: <?php echo $color; ?>!important;
}


.select2-container--default .select2-selection--single {
  border-color: <?php echo $color; ?>!important;

}

.select2-results__option.select2-results__option--selected,
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
  background-color:  <?php echo $color; ?>!important;

}

.payment-card-title {

  background-color:  <?php echo $color; ?>!important;

}

.payment-system-list {
  --hover-border-color:  <?php echo $color; ?>!important;
  background-color: <?php echo $secondColor; ?>!important;
}

.payment-item:has(.payment-item__radio:checked) .payment-item__check {
  border: 3px solid <?php echo $color; ?>!important;
}

.payment-item__check {

  border: 1px solid  <?php echo $color; ?>!important;
 
}


.payment-item__btn{
    background:  <?php echo $color; ?>!important;
}

.select2-dropdown {
  background-color: <?php echo $secondColor; ?>!important;

}



.verification-code-wrapper {

    background-color: <?php echo $secondColor; ?>!important;
   
}


<?php
/*
Plugin Name: ReserveerEenvoudig
Plugin URI: https://reserveereenvoudig.nl/
Description: Zet de ReserveerEenvoudig widget gemakkelijk op uw website.
Version: 1.1.4
Author: Taurus Kassasystemen
Author URI: https://www.tauruskassasystemen.nl/
License: GPL2
*/

// Blokkeer directe toegang tot het bestand
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Voeg een menu-item toe aan het WordPress-dashboard
function reserveereenvoudig_menu() {
    add_options_page(
        'ReserveerEenvoudig Instellingen', // Paginatitel
        'ReserveerEenvoudig', // Menu titel
        'manage_options', // Vereiste capaciteit
        'reserveereenvoudig', // Menu slug
        'reserveereenvoudig_instellingen_pagina' // Callback voor inhoud
    );
}
add_action('admin_menu', 'reserveereenvoudig_menu');

// Creëer de instellingenpagina
function reserveereenvoudig_instellingen_pagina() {
    ?>
    <div class="wrap">
        <h2>ReserveerEenvoudig Instellingen</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('reserveereenvoudig-instellingen-groep');
            do_settings_sections('reserveereenvoudig');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registreer de instellingen
function reserveereenvoudig_instellingen() {
    register_setting(
        'reserveereenvoudig-instellingen-groep', // Optiegroep
        'reserveereenvoudig_bedrijfs_guid', // Optienaam
        array(
            'type' => 'string',
            'description' => 'BedrijfsGUID voor ReserveerEenvoudig Widget',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        )
    );

    add_settings_section(
        'reserveereenvoudig-hoofdsectie', // ID
        'Bedrijf', // Titel
        'reserveereenvoudig_hoofdsectie_callback', // Callback voor de beschrijving
        'reserveereenvoudig' // Pagina
    );

    add_settings_field(
        'reserveereenvoudig_bedrijfs_guid', // ID
        'BedrijfsGUID', // Titel
        'reserveereenvoudig_bedrijfs_guid_callback', // Callback voor de input
        'reserveereenvoudig', // Pagina
        'reserveereenvoudig-hoofdsectie' // Sectie
    );
}
add_action('admin_init', 'reserveereenvoudig_instellingen');

// Callback voor de hoofdsectie
function reserveereenvoudig_hoofdsectie_callback() {
    echo 'Vul hieronder de BedrijfsGUID in:';
}

// Callback voor het invoerveld
function reserveereenvoudig_bedrijfs_guid_callback() {
    $bedrijfs_guid = get_option('reserveereenvoudig_bedrijfs_guid');
    echo '<input type="text" name="reserveereenvoudig_bedrijfs_guid" value="' . esc_attr($bedrijfs_guid) . '" />';
}

// Voeg het script toe aan de front-end
function reserveereenvoudig_script_inladen() {
    $bedrijfs_guid = get_option('reserveereenvoudig_bedrijfs_guid');
    if ($bedrijfs_guid) {
        echo '<script id="TaurusWidget" type="text/javascript" Bedrijf="' . esc_attr($bedrijfs_guid) . '" src="https://reserveereenvoudig.nl/widget.js"></script>';
    }
}
add_action('wp_footer', 'reserveereenvoudig_script_inladen');
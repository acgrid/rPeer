<?php
/**
 * A library for encapsulation of BitTorrent Peer Presentation and Manipulation
 * Designed to replace the coupling code on an existing trackers, eg.
 * Switch the backend smoothly from simple MySQL memory staging environment to heavy-loaded production environment.
 * Applying different observers to act as site-specific logics with general updates shared and mainteined by this library.
 * Enable/Disable additional features in different site without touch kenerl codes.
 * 
 * @author acgrid
 * @package rPeer
 * @license GPL
 */

namespace rPeer;

define('ABSPATH', dirname(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . ABSPATH);

/*
 * Loaded common files
 */
require 'Exceptions.php';
require 'Abstracts.php';
require 'Utility.php';
require 'Facade.php';

/*
 * HOWTO USE THEN
 * (1) Call proper Facade::initTypical_Vendor or Facade::initAutoload, which differ by the loading strategies
 * (2) Call Facade::getXXX for pre-created or pre-rontine objects or just new instances if autoloading is used
 * (3) I'm the announce: Create custom chain fot the PrivatePeer creation following NativePeer instance, finally insert/update qualified peer and echo the peer list 
 * 	   I'm peer querier: Make use of PeerDatabaseProvider and its wrappers to obtain data you need
 */
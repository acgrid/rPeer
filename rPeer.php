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

/**
 * The library portal is a loader to determine a typical or custom components scheme
 * Choose a typical routine to bypass the autoload especially for the massive announce and scrape access
 * Or just go straight, such as performing highly-personal queries, for maximum flexible utilizing SPL autoload
 */

class Facade
{
	
}

/**
 * 
 */
 
<?php
/**
 * @package     Minima
 * @subpackage  mod_mypanel
 * @author      Marco Barbosa
 * @copyright   Copyright (C) 2010 Marco Barbosa. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$items     = ModMypanelHelper::getItems();
$invisible = false;

$nPages  = ceil( count($items) / 9);

// hide arrows and pagination if items lower or equal 9
if (count($items) <= 9) {
    $invisible = true;
}

?>

<div id="panel">

    <!-- search field -->
    <!--<input type="text" id="search-term" placeholder="What are you looking for?" />-->
    
    
    <!-- dots pagination -->
    <ul id="panel-pagination" <?php if ($invisible) echo "class=\"hide\""; ?>>
        <?php for($i=0; $i < $nPages; $i++) : ?>
            <li <?php if($i == 0) echo "class=\"current\"" ?> id="panel-pagination-<?php echo $i;?>"></li>
        <?php endfor; ?>
    </ul>
    

    <!-- prev button -->
    <a href="#" id="prev" <?php if ($invisible) echo "class=\"invisible\""; ?>><span class="arrow"></span></a>
    
    <nav id="panel-list">
        <?php
            // number of extensions
            $count = 0;

            // standard components that we have the icons ready
            $jComponents = array("com_banners", "com_contact", "com_messages", "com_newsfeeds", "com_redirect", "com_search", "com_weblinks");

            foreach ($items as $item) {
                
                // reset local vars
                $isIconFound = false; 
                $class = "";

                // get the description from the language file (100 chars)
                $description = strip_tags( substr(JText::_(''.strtoupper($item->title).'_XML_DESCRIPTION'), 0, 100) );
                
                // get the title of the component             
                $title = JText::_(''.strtoupper($item->title));
                
                // do we have a description?
                // show the "no description available" message if not
                if (strpos($description, '_XML_DESCRIPTION') !== false) {
                    $description = JText::_('TPL_MINIMA_NODESCRIPTION');
                }

                // if it's a standard extension, add the class to use the sprites
                if (in_array(strtolower($item->element), $jComponents)) {
                    
                    // getting the component image class
                    $arrClass = explode(":", $item->img);
                    
                    // concatenate with icon-48
                    $class = "icon-48-".$arrClass[1];

                } else {

                    // component dev already specifies image path
                    // if any '16' is present in the title change it to '48'
                    $img = str_replace('16', '48', $item->img);

                    // does the supplied image exist?
                    if (file_exists($img)) {
                        
                        // make sure image found is not too small
                        $imgDimensions = getimagesize($img);
                        
                        // $imgDimensions[0] contains the width
                        if ($imgDimensions[0] >= 48) {
                            $isIconFound = true;
                        }
                        
                    // start checking for alternative paths
                    } else {
                        
                        // break full path into parts
                        $imgFullPath = explode("/", $img);
                        // get the last one (image name)
                        $imgName = $imgFullPath[count($imgFullPath)-1];
                        
                        // foreach array of possibilities

                        // look for the img in the header path
                        //$item->element
                        /*if (file_exists()) {
                            
                        // look for the img in the media path
                        } else if () {
                            
                        }*/
                        
                        
                    }

                    // last fallback if img still not found
                    if (!$isIconFound) {
                        $class = "icon-48-generic";
                    }

                }
        
                // new list for first of every 10th item
                if ($count % 9 === 0) {
                    echo "<ul>";
                }

                // standard extension
                if (!empty($class)) { 
        ?>
                <li>
                    <a href="<?php echo $item->link; ?>" class="<?php echo $class; ?>"><?php echo $title; ?>
                        <span class="ext-desc"><?php echo $description; ?></span>
                    </a>
                </li>
        <?php   
                // not standard, add an image instead of a class
                } else { 
        ?>
                <li class="ext">
                    <a href="<?php echo $item->link; ?>" class="<?php echo $class; ?>">
                        <figure>
                            <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" />
                        </figure>
                        <?php echo $title; ?>
                        <span class="ext-desc"><?php echo $description; ?></span>
                    </a>
                </li>
        <?php   
                } // end of if !empty($class)
                
                // close list for first of every 10th item
                if ($count % 9 === 8) {
                    echo "</ul>";
                }

                // one more extension
                $count++;
                         
        }; //end of loop
         
        ?>
    </nav>

    <!-- next button -->
    <a href="#" id="next" <?php if ($invisible) echo "class=\"invisible\""; ?>><span class="arrow"></span></a>
</div>

<div class="clr"></div>

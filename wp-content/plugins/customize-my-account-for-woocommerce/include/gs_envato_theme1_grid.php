<?php

$output .= '<div class="container">';
	$output .= '<div class="row">';

        for ($i=0; $i <$count; $i++) { 

            if (empty($gs_envato_items[$i]['live_preview_url'])) {
                return;
            }

            $url = $gs_envato_items[$i]['url'];

            $url = 'https://1.envato.market/c/1314780/275988/4415?u='.rawurlencode($url).'';

            $output .= '<div class="col-md-'.$columns.' col-sm-6 col-xs-12">';
                $output .= '<div class="single-item">';
                    $output .= '<a href="'.$url.'"><img src="'.$gs_envato_items[$i]['live_preview_url'].'"/></a>';

                    $output .= "<div class='single-envitem-title'>";
                    
                    $output .= '<div class="gs-envitem-name"><a href="'.$url.'">'.$gs_envato_items[$i]['item'].'</a></div>';    
                    
                    $output .= "</div>";
                $output .= '</div>';
            $output .= '</div>'; // end col
        }

    $output .= '</div>'; // end row
$output .= '</div>'; // end container
return $output;
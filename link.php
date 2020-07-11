<?php
    include_once ("includes/parser/simple_html_dom.php");
    $post ='https://news.abs-cbn.com/news/07/11/20/sws-3-out-of-4-filipinos-say-congress-should-renew-abs-cbn-franchise';
    $body_headers = @get_headers($post);
    if ($body_headers || $body_headers[0] != 'HTTP/1.1 404 Not Found'){
        $body_headers = file_get_html($post);
        foreach($body_headers->find('img') as $element){
            $link_image = $element->src;
            if (substr($link_image,0,4=="http")){
                break;
            }
        }
        foreach ($body_headers->find('h1') as $element){
            $link_h1 = $element->src;
            echo "head: " . $link_h1 . "<br/>";
        break;
        }
        $post = "<a href = '$post'><div class='card mb-3>
                    <div class = 'row no-gutters'>
                        <div class = 'col-md-4'>
                            <img src ='$link_image' class ='card-img'>
                        </div>
                        <div class ='col-md-8'>
                            <div class = 'card-body'>
                                <h5 class = 'card-title'> $link_h1 </h5>
                                <p class = 'card-text'> $post </p>
                            </div>
                        </div>
                    </div>
                </div></a>";

    } 
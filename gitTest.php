<?php

function result()
{
    for ($i = 0; $i < 10; $i++) {
        $rand[] = $i ;
    }
    shuffle($rand);
    echo json_encode($rand);
}
result();
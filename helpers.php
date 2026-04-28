<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

function jsonResponse($ok, $msg = '', $data = []) {
    echo json_encode(['ok' => $ok, 'msg' => $msg, 'data' => $data]);
    exit;
}

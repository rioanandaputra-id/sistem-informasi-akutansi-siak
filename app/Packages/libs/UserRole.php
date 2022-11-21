<?php

if (!function_exists('userRole')) {
    function userRole($idRole)
    {
        $userRole = DB::select("SELECT rusr.*, rol.role_name FROM role_users AS rusr JOIN roles AS rol ON rol.id_role = rusr.id_role AND rol.deleted_at IS NULL WHERE rusr.id_user = ? AND rusr.deleted_at IS NULL LIMIT 1", [$idRole]);
        return $userRole;
    }
}

if (!function_exists('userDivisi')) {
    function userDivisi($idDivisi)
    {
        $userDivisi = DB::select("SELECT * FROM divisi WHERE id_divisi = ? AND deleted_at IS NULL LIMIT 1", [$idDivisi]);
        return $userDivisi;
    }
}

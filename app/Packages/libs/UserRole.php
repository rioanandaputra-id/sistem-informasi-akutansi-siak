<?php

if (!function_exists('userRole')) {
    function userRole($idRole)
    {
        $qRole = DB::select("SELECT * FROM roles WHERE id_role = ? AND deleted_at IS NULL", [$idRole]);
        $roleName = $qRole ? $qRole[0]->role_name : '-';
        return $roleName;
    }
}

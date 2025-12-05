<?php

use App\Models\Log\LogActivityModel;

if (!function_exists('addLog')) {
    function addLog($activity)
    {
        $auth = service('authentication');
        $user = $auth->user();

        if (!$user) {
            return false;
        }

        // Ambil role
        $groupModel = model('Myth\Auth\Models\GroupModel');
        $roles = $groupModel->getGroupsForUser($user->id);

        $roleName = isset($roles[0]['name']) ? $roles[0]['name'] : 'unknown';

        $log = new LogActivityModel();

        return $log->insert([
            'user_id'    => $user->id,
            'role'       => $roleName,
            'activity'   => $activity,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

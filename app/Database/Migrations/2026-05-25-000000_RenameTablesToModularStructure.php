<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameTablesToModularStructure extends Migration
{
    public function up()
    {
        // 1. Modul Perumahan
        if ($this->db->tableExists('rtlh_penerima')) $this->forge->renameTable('rtlh_penerima', 'perumahan_rtlh_penerima');
        if ($this->db->tableExists('rtlh_rumah')) $this->forge->renameTable('rtlh_rumah', 'perumahan_rtlh_rumah');
        if ($this->db->tableExists('rtlh_kondisi_rumah')) $this->forge->renameTable('rtlh_kondisi_rumah', 'perumahan_rtlh_kondisi');
        if ($this->db->tableExists('rtlh_history_perubahan')) $this->forge->renameTable('rtlh_history_perubahan', 'perumahan_rtlh_history');
        if ($this->db->tableExists('rtlh_bansos')) $this->forge->renameTable('rtlh_bansos', 'perumahan_rtlh_bansos');
        if ($this->db->tableExists('backlog_data')) $this->forge->renameTable('backlog_data', 'perumahan_backlog_agregat');

        // 2. Modul Permukiman
        if ($this->db->tableExists('arsinum')) $this->forge->renameTable('arsinum', 'permukiman_arsinum');
        if ($this->db->tableExists('pisew')) $this->forge->renameTable('pisew', 'permukiman_pisew');
        if ($this->db->tableExists('psu_jalan')) $this->forge->renameTable('psu_jalan', 'permukiman_psu_jalan');
        if ($this->db->tableExists('wilayah_kumuh')) $this->forge->renameTable('wilayah_kumuh', 'permukiman_wilayah_kumuh');

        // 3. Modul Pertanahan
        if ($this->db->tableExists('aset_tanah')) $this->forge->renameTable('aset_tanah', 'pertanahan_aset');

        // 4. Modul System
        if ($this->db->tableExists('users')) $this->forge->renameTable('users', 'sys_users');
        if ($this->db->tableExists('roles')) $this->forge->renameTable('roles', 'sys_roles');
        if ($this->db->tableExists('permissions')) $this->forge->renameTable('permissions', 'sys_permissions');
        if ($this->db->tableExists('role_permissions')) $this->forge->renameTable('role_permissions', 'sys_role_permissions');
        if ($this->db->tableExists('user_desa')) $this->forge->renameTable('user_desa', 'sys_user_desa');
        if ($this->db->tableExists('settings')) $this->forge->renameTable('settings', 'sys_settings');
        if ($this->db->tableExists('ref_master')) $this->forge->renameTable('ref_master', 'sys_ref_master');
        if ($this->db->tableExists('trash_data')) $this->forge->renameTable('trash_data', 'sys_trash');
    }

    public function down()
    {
        // Revert 1. Modul Perumahan
        if ($this->db->tableExists('perumahan_rtlh_penerima')) $this->forge->renameTable('perumahan_rtlh_penerima', 'rtlh_penerima');
        if ($this->db->tableExists('perumahan_rtlh_rumah')) $this->forge->renameTable('perumahan_rtlh_rumah', 'rtlh_rumah');
        if ($this->db->tableExists('perumahan_rtlh_kondisi')) $this->forge->renameTable('perumahan_rtlh_kondisi', 'rtlh_kondisi_rumah');
        if ($this->db->tableExists('perumahan_rtlh_history')) $this->forge->renameTable('perumahan_rtlh_history', 'rtlh_history_perubahan');
        if ($this->db->tableExists('perumahan_rtlh_bansos')) $this->forge->renameTable('perumahan_rtlh_bansos', 'rtlh_bansos');
        if ($this->db->tableExists('perumahan_backlog_agregat')) $this->forge->renameTable('perumahan_backlog_agregat', 'backlog_data');

        // Revert 2. Modul Permukiman
        if ($this->db->tableExists('permukiman_arsinum')) $this->forge->renameTable('permukiman_arsinum', 'arsinum');
        if ($this->db->tableExists('permukiman_pisew')) $this->forge->renameTable('permukiman_pisew', 'pisew');
        if ($this->db->tableExists('permukiman_psu_jalan')) $this->forge->renameTable('permukiman_psu_jalan', 'psu_jalan');
        if ($this->db->tableExists('permukiman_wilayah_kumuh')) $this->forge->renameTable('permukiman_wilayah_kumuh', 'wilayah_kumuh');

        // Revert 3. Modul Pertanahan
        if ($this->db->tableExists('pertanahan_aset')) $this->forge->renameTable('pertanahan_aset', 'aset_tanah');

        // Revert 4. Modul System
        if ($this->db->tableExists('sys_users')) $this->forge->renameTable('sys_users', 'users');
        if ($this->db->tableExists('sys_roles')) $this->forge->renameTable('sys_roles', 'roles');
        if ($this->db->tableExists('sys_permissions')) $this->forge->renameTable('sys_permissions', 'permissions');
        if ($this->db->tableExists('sys_role_permissions')) $this->forge->renameTable('sys_role_permissions', 'role_permissions');
        if ($this->db->tableExists('sys_user_desa')) $this->forge->renameTable('sys_user_desa', 'user_desa');
        if ($this->db->tableExists('sys_settings')) $this->forge->renameTable('sys_settings', 'settings');
        if ($this->db->tableExists('sys_ref_master')) $this->forge->renameTable('sys_ref_master', 'ref_master');
        if ($this->db->tableExists('sys_trash')) $this->forge->renameTable('sys_trash', 'trash_data');
    }
}

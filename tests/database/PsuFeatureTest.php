<?php

namespace Tests\Database;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\PsuJalanModel;

/**
 * @internal
 */
final class PsuFeatureTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $seed    = ''; // We will manually seed in setUp or tests

    protected function setUp(): void
    {
        parent::setUp();
        
        // Reset session
        $_SESSION = [];
    }

    private function setAdminSession()
    {
        $sessionData = [
            'isLoggedIn' => true,
            'user_id'    => 1,
            'username'   => 'admin',
            'role_name'  => 'admin', // Admin bypasses has_permission check in auth_helper
            'permissions' => []
        ];
        session()->set($sessionData);
    }

    private function setPetugasSession(array $permissions = [])
    {
        $sessionData = [
            'isLoggedIn' => true,
            'user_id'    => 2,
            'username'   => 'petugas',
            'role_name'  => 'petugas',
            'permissions' => $permissions
        ];
        session()->set($sessionData);
    }

    public function testIndexPageAccessDenied()
    {
        // No session = no access if there's a filter, 
        // but index() in Psu.php doesn't check has_permission.
        // It's likely protected by AuthFilter in Routes.
        
        $result = $this->get('/psu');
        
        // If not logged in, it should redirect to login (assuming AuthFilter)
        // Let's check Routes.php to be sure
        $result->assertStatus(302);
    }

    public function testIndexPageSuccess()
    {
        $this->setAdminSession();
        
        $result = $this->get('/psu');
        $result->assertStatus(200);
        $result->assertSee('PSU Jaringan Jalan');
    }

    public function testCreatePageAccessDenied()
    {
        $this->setPetugasSession([]); // No permissions
        
        $result = $this->get('/psu/create');
        $result->assertRedirect();
        $result->assertSessionHas('error', 'Izin ditolak.');
    }

    public function testCreatePageSuccess()
    {
        $this->setAdminSession();
        
        $result = $this->get('/psu/create');
        $result->assertStatus(200);
        $result->assertSee('Tambah Jaringan Jalan');
    }

    public function testStorePsuSuccess()
    {
        $this->setAdminSession();
        
        $data = [
            'nama_jalan' => 'Jalan Test',
            'id_csv'     => 'CSV-001',
            'jalan'      => 1500.50,
            'wkt'        => 'LINESTRING(0 0, 1 1)'
        ];

        $result = $this->post('/psu/store', $data);
        
        $result->assertRedirectTo('/psu');
        $result->assertSessionHas('success', 'Data berhasil ditambahkan.');

        $this->seeInDatabase('psu_jalan', ['nama_jalan' => 'Jalan Test']);
    }

    public function testStorePsuValidationError()
    {
        $this->setAdminSession();
        
        $data = [
            'nama_jalan' => '', // Required
            'wkt'        => '', // Required
            'jalan'      => 'abc' // Must be numeric
        ];

        $result = $this->post('/psu/store', $data);
        
        $result->assertRedirect();
        $result->assertSessionHas('errors');
    }

    public function testEditPageSuccess()
    {
        $this->setAdminSession();
        
        $model = new PsuJalanModel();
        $id = $model->insert([
            'nama_jalan' => 'Jalan To Edit',
            'wkt'        => 'POINT(0 0)',
            'jalan'      => 100
        ]);

        $result = $this->get("/psu/edit/$id");
        $result->assertStatus(200);
        $result->assertSee('Edit Jaringan Jalan');
        $result->assertSee('Jalan To Edit');
    }

    public function testUpdatePsuSuccess()
    {
        $this->setAdminSession();
        
        $model = new PsuJalanModel();
        $id = $model->insert([
            'nama_jalan' => 'Old Name',
            'wkt'        => 'POINT(0 0)',
            'jalan'      => 100
        ]);

        $data = [
            'nama_jalan' => 'New Name',
            'wkt'        => 'POINT(1 1)',
            'jalan'      => 200
        ];

        $result = $this->post("/psu/update/$id", $data);
        
        $result->assertRedirectTo('/psu');
        $result->assertSessionHas('success', 'Data berhasil diperbarui.');

        $this->seeInDatabase('psu_jalan', [
            'id' => $id,
            'nama_jalan' => 'New Name',
            'jalan' => 200
        ]);
    }

    public function testDeletePsuSuccess()
    {
        $this->setAdminSession();
        
        $model = new PsuJalanModel();
        $id = $model->insert([
            'nama_jalan' => 'To Be Deleted',
            'wkt'        => 'POINT(0 0)',
            'jalan'      => 100
        ]);

        $result = $this->post("/psu/delete/$id", []);
        
        $result->assertRedirectTo('/psu');
        $result->assertSessionHas('success', 'Data berhasil dihapus.');

        $this->dontSeeInDatabase('psu_jalan', ['id' => $id]);
    }

    public function testDetailPsu()
    {
        $this->setAdminSession();
        
        $model = new PsuJalanModel();
        $id = $model->insert([
            'nama_jalan' => 'Detail Jalan',
            'wkt'        => 'POINT(0 0)',
            'jalan'      => 100
        ]);

        $result = $this->get("/psu/detail/$id");
        $result->assertStatus(200);
        $result->assertSee('Detail Jalan');
    }
}

describe('CRUD PISEW - Full Cycle', () => {
  const uniqueJob = 'PISEW JOB CYPRESS ' + Date.now();
  const updatedJob = 'PISEW JOB UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on PISEW', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/pisew');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/pisew/create');
    
    cy.get('input[name="jenis_pekerjaan"]').type(uniqueJob);
    cy.get('input[name="lokasi_desa"]').type('DESA TEST');
    cy.get('input[name="kecamatan"]').type('KECAMATAN TEST');
    cy.get('input[name="pelaksana"]').type('PELAKSANA CYPRESS');
    cy.get('input[name="anggaran"]').type('150000000');
    cy.get('input[name="tahun"]').type('2024');
    cy.get('input[name="sumber_dana"]').type('APBN');
    
    cy.contains('button', 'Simpan Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="search"]').clear().type(uniqueJob + '{enter}');
    cy.contains(uniqueJob).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueJob).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains('Edit').click({ force: true });
    
    cy.get('input[name="jenis_pekerjaan"]').clear().type(updatedJob);
    cy.contains('button', 'Perbarui Data').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/pisew');
    cy.get('input[name="search"]').clear().type(updatedJob + '{enter}');
    
    // Klik hapus
    cy.contains(updatedJob).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Modal Konfirmasi
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains(/dipindahkan ke Recycle Bin/i, { timeout: 15000 }).should('be.visible');
    cy.contains(updatedJob).should('not.exist');
  });
});

describe('CRUD Manajemen User - Full Cycle', () => {
  const uniqueUser = 'user_test_' + Date.now();
  const updatedInstansi = 'INSTANSI UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on Users', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/users');
    cy.contains('Tambah User').click();
    cy.url().should('include', '/users/create');
    
    cy.get('input[name="username"]').type(uniqueUser);
    cy.get('input[name="password"]').type('password123');
    cy.get('input[name="instansi"]').type('DINAS TEST CYPRESS');
    
    // Select Role (Petugas)
    cy.get('select[name="role_id"]').select('2'); // Assuming 2 is Petugas

    // TomSelect interaction for villages (RTLH & Kumuh)
    // We use force: true because TomSelect hides the original select
    cy.get('#desa_rtlh').select(['7307070005'], { force: true });
    cy.get('#desa_kumuh').select(['7307070005'], { force: true });
    
    cy.contains('button', 'Simpan Akun').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/users');
    cy.contains('User berhasil ditambahkan').should('be.visible');
    
    // Cari data di tabel
    cy.contains(uniqueUser).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueUser).closest('tr').find('a[href*="/edit/"]').click();
    cy.url().should('include', '/users/edit/');
    
    // Ubah Instansi
    cy.get('input[name="instansi"]').clear().type(updatedInstansi);
    cy.contains('button', 'Perbarui Profil').click({ force: true });

    // Verifikasi Update
    cy.contains('User berhasil diperbarui').should('be.visible');
    cy.contains(updatedInstansi).should('exist');

    // 3. DELETE
    cy.contains(uniqueUser).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Modal Konfirmasi SIBARUKI
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains('User telah dipindahkan ke Recycle Bin.').should('be.visible');
    cy.contains(uniqueUser).should('not.exist');
  });
});

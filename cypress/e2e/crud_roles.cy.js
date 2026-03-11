describe('CRUD Manajemen Role - Full Cycle', () => {
  const uniqueRole = 'ROLE TEST ' + Date.now();
  const updatedRole = 'ROLE UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on Roles', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/roles');
    cy.contains('TAMBAH ROLE').click();
    cy.url().should('include', '/roles/create');
    
    cy.get('input[name="role_name"]').type(uniqueRole);
    
    // Select Scope (Local)
    cy.get('input[name="scope"][value="local"]').check({ force: true });

    // Select some permissions
    // We check the first few checkboxes found in the permission matrix
    cy.get('input[name="permissions[]"]').first().check({ force: true });
    cy.get('input[name="permissions[]"]').eq(1).check({ force: true });
    cy.get('input[name="permissions[]"]').eq(4).check({ force: true });
    
    cy.contains('button', 'SIMPAN ROLE').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/roles');
    cy.contains('Role berhasil ditambahkan').should('be.visible');
    
    // Cari data di list (Role ditampilkan dalam bentuk card)
    cy.contains('h3', uniqueRole).should('exist');

    // 2. READ & UPDATE
    cy.contains('h3', uniqueRole).parents('.group').find('a:contains("EDIT")').click();
    cy.url().should('include', '/roles/edit/');
    
    // Ubah Nama Role
    cy.get('input[name="role_name"]').clear().type(updatedRole);
    
    // Tambah satu permission lagi
    cy.get('input[name="permissions[]"]').eq(2).check({ force: true });

    cy.contains('button', 'PERBARUI ROLE').click({ force: true });

    // Verifikasi Update
    cy.contains('Role berhasil diperbarui').should('be.visible');
    cy.contains('h3', updatedRole).should('exist');

    // 3. DELETE
    // Klik tombol delete (ikon trash di card)
    cy.contains('h3', updatedRole).parents('.group').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Modal Konfirmasi SIBARUKI
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    // Di index roles, flash message biasanya 'Role berhasil dihapus'
    // Tapi mari kita pastikan card-nya hilang
    cy.contains('h3', updatedRole).should('not.exist');
  });
});

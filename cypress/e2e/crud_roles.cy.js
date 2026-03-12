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
    cy.get('input[name="scope"][value="local"]').check({ force: true });

    // Select some permissions
    cy.get('input[name="permissions[]"]').first().check({ force: true });
    cy.get('input[name="permissions[]"]').eq(1).check({ force: true });
    
    cy.contains('button', 'SIMPAN ROLE').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    cy.contains('h3', uniqueRole).should('exist');

    // 2. READ & UPDATE
    cy.contains('h3', uniqueRole).parents('.group').find('a:contains("EDIT")').click();
    cy.get('input[name="role_name"]').clear().type(updatedRole);
    cy.contains('button', 'PERBARUI ROLE').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    cy.contains('h3', updatedRole).should('exist');

    // 3. DELETE
    cy.visit('http://localhost:8080/roles');
    cy.contains('h3', updatedRole).parents('.group').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Modal Konfirmasi
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains(updatedRole).should('not.exist');
  });
});

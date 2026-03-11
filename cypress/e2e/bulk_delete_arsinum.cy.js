describe('Bulk Delete ARSINUM - Full Simulation', () => {
  const timestamp = Date.now();
  const project1 = 'ARSINUM BULK 1 ' + timestamp;
  const project2 = 'ARSINUM BULK 2 ' + timestamp;
  const project3 = 'ARSINUM BULK 3 ' + timestamp;

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should create 3 Arsinum records and then bulk delete them', () => {
    // --- 1. CREATE 3 RECORDS ---
    const projects = [project1, project2, project3];
    
    projects.forEach(projectName => {
        cy.visit('http://localhost:8080/arsinum');
        cy.contains('Tambah Data').click();
        cy.get('input[name="jenis_pekerjaan"]').type(projectName);
        cy.get('input[name="volume"]').type('1 UNIT');
        cy.get('input[name="anggaran"]').type('100000000');
        cy.get('input[name="desa"]').type('DESA BULK');
        cy.get('input[name="kecamatan"]').type('SINJAI');
        cy.contains('button', 'Simpan Data').click({ force: true });
        cy.contains('Data Arsinum berhasil ditambahkan').should('be.visible');
    });

    // --- 2. BULK DELETE ---
    cy.visit('http://localhost:8080/arsinum');
    
    // Cari data bulk menggunakan filter pencarian untuk memastikan ketiganya muncul
    cy.get('input[name="search"]').clear().type('ARSINUM BULK ' + timestamp + '{enter}');
    
    // Centang satu per satu atau gunakan Select All
    // Di sini kita coba centang individu untuk memastikan fungsionalitas row checkbox
    cy.contains(project1).closest('tr').find('.row-checkbox').check();
    cy.contains(project2).closest('tr').find('.row-checkbox').check();
    cy.contains(project3).closest('tr').find('.row-checkbox').check();

    // Pastikan Floating Bar muncul
    cy.get('#bulk-action-bar').should('not.have.class', '-translate-y-full');
    cy.get('#selected-count').should('contain', '3 TERPILIH');

    // Klik Hapus Terpilih
    cy.contains('button', 'Hapus Terpilih').click();

    // Konfirmasi pada Modal
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Pesan Sukses Massal
    cy.contains('3 data berhasil dihapus', { timeout: 15000 }).should('be.visible');

    // Pastikan data sudah tidak ada di tabel
    cy.get('input[name="search"]').clear().type('ARSINUM BULK ' + timestamp + '{enter}');
    cy.get('tbody').should('contain', 'Data tidak ditemukan');
  });
});

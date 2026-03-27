describe('CRUD RTLH - Full Cycle', () => {
  const uniqueName = 'PENERIMA TEST ' + Date.now();
  const updatedName = 'PENERIMA UPDATED ' + Date.now();
  const nik = '1234567890123456';

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on RTLH with Photo Documentation', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/rtlh');
    cy.contains('Tambah').click();
    cy.url().should('include', '/rtlh/create');
    
    // Identitas
    cy.get('input[name="nama_kepala_keluarga"]').type(uniqueName);
    cy.get('input[name="nik"]').type(nik);
    cy.get('input[name="no_kk"]').type('6543210987654321');
    
    // Upload Foto (Simulasi)
    const fixtureFile = 'sinjai.png';
    cy.get('input[name="foto_depan"]').attachFile(fixtureFile);
    cy.get('#foto_depan_preview').should('have.class', 'border-solid'); // Verifikasi preview muncul

    // Wilayah (Gunakan selector yang sesuai dengan UI mewah baru)
    cy.get('select[name="desa"]').select(1);
    
    // Koordinat
    cy.get('input[name="lokasi_koordinat"]').type('POINT(120.2536 -5.1245)', { force: true });
    
    // Submit
    cy.contains('Simpan Semua Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="keyword"]').clear().type(uniqueName);
    cy.get('button').find('[data-lucide="search"]').parent().click();
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    
    // Verifikasi Foto di Detail
    cy.contains('Dokumentasi Visual Rumah').should('be.visible');
    cy.get('img[alt="Tampak Depan"]').should('be.visible');

    // Klik tombol Perbarui Data
    cy.contains('Perbarui Data').click();
    
    // Ubah Alamat
    cy.get('textarea[name="alamat_detail"]').clear().type('ALAMAT UPDATED TEST');
    
    // Ganti Foto Samping
    cy.get('input[name="foto_samping"]').attachFile(fixtureFile);

    // Klik tombol simpan (Perbarui Data Terpadu)
    cy.contains('button', 'Perbarui Data Terpadu').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/rtlh');
    cy.get('input[name="keyword"]').clear().type(uniqueName);
    cy.get('button').find('[data-lucide="search"]').parent().click();
    
    // Klik hapus (Gunakan selector Lucide Icon)
    cy.contains(uniqueName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Modal Konfirmasi (SweetAlert/Custom Modal)
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus (Pesan Recycle Bin)
    cy.contains(/Recycle Bin/i, { timeout: 15000 }).should('be.visible');
    cy.contains(uniqueName).should('not.exist');
  });
});

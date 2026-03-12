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

  it('should perform full CRUD on RTLH', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/rtlh');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/rtlh/create');
    
    // Identitas
    cy.get('input[name="nama_kepala_keluarga"]').type(uniqueName);
    cy.get('input[name="nik"]').type(nik);
    cy.get('input[name="no_kk"]').type('6543210987654321');
    
    // Wilayah
    cy.get('select[name="desa_id"]').select(1);
    
    // Koordinat
    cy.get('#lokasi_koordinat').type('POINT(120.2536 -5.1245)', { force: true });
    
    // Submit
    cy.contains('Simpan Semua Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    
    // Klik tombol Perbarui Data (Link ke halaman edit)
    cy.contains('Perbarui Data').click();
    
    // Ubah Desa (di edit.php nama fieldnya 'desa' dan tipenya input text)
    cy.get('input[name="desa"]').clear().type('DESA UPDATED');
    
    // Klik tombol simpan (Perbarui Data Terpadu)
    cy.contains('button', 'Perbarui Data Terpadu').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/rtlh');
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    
    // Klik hapus
    cy.contains(uniqueName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Modal Konfirmasi
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

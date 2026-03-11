describe('Lifecycle RTLH to RLH - Full Simulation', () => {
  const uniqueName = 'LIFECYCLE TEST ' + Date.now();
  const nik = '9999888877776666';

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should create RTLH, mark as Tuntas (RLH), and then delete', () => {
    // 1. CREATE RTLH
    cy.visit('http://localhost:8080/rtlh');
    cy.contains('Tambah Data').click();
    
    cy.get('input[name="nama_kepala_keluarga"]').type(uniqueName);
    cy.get('input[name="nik"]').type(nik);
    cy.get('input[name="no_kk"]').type('1111222233334444');
    cy.get('select[name="desa_id"]').select(1);
    cy.get('#lokasi_koordinat').type('POINT(120.2536 -5.1245)', { force: true });
    
    // Penilaian Teknis Minimal
    cy.get('select[name="st_pondasi"]').select(1);
    cy.get('select[name="st_kolom"]').select(1);
    cy.get('select[name="st_balok"]').select(1);
    cy.get('select[name="st_sloof"]').select(1);
    cy.get('select[name="mat_atap"]').select(1);
    cy.get('select[name="st_atap"]').select(1);
    cy.get('select[name="mat_dinding"]').select(1);
    cy.get('select[name="st_dinding"]').select(1);
    cy.get('select[name="mat_lantai"]').select(1);
    cy.get('select[name="st_lantai"]').select(1);

    cy.contains('Simpan Semua Data').click({ force: true });
    cy.contains('Data RTLH berhasil ditambahkan').should('be.visible');

    // 2. SEARCH & MARK AS TUNTAS (RLH)
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    
    // Klik tombol Tandai Tuntas Bansos
    cy.contains('Tandai Tuntas Bansos').click();
    
    // Isi Modal Tuntas
    cy.get('#modal-tuntas').should('be.visible');
    cy.get('input[name="tahun_bansos"]').clear().type('2024');
    cy.get('input[name="program_bansos"]').type('BSPS CYPRESS TEST');
    cy.contains('button', 'Konfirmasi Tuntas').click();

    // Verifikasi Berhasil & Pindah Tab
    cy.contains('Data RTLH berhasil ditandai sebagai Tuntas').should('be.visible');
    
    // Pastikan di tab "Belum Menerima" (Default) data sudah tidak ada
    cy.visit('http://localhost:8080/rtlh');
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.get('tbody').should('contain', 'Data tidak ditemukan');

    // 3. CHECK IN RLH TAB & DELETE
    // Pindah ke tab Telah Menerima (RLH)
    cy.contains('Telah Menerima (RLH)').click();
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // Hapus data dari tab RLH
    cy.contains(uniqueName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Konfirmasi Hapus
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    cy.contains('Data RTLH berhasil dihapus').should('be.visible');
    cy.contains(uniqueName).should('not.exist');
  });
});

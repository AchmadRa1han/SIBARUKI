describe('RTLH CRUD Testing - Pure UI Interaction Flow', () => {
  const timestamp = Date.now();
  const randSuffix = Math.floor(1000000000 + Math.random() * 9000000000);
  const nik = '730705' + randSuffix;
  const no_kk = '730705' + randSuffix;
  const nama = 'Cypress Tester ' + timestamp;
  const newNama = 'Cypress Tester Updated ' + timestamp;

  beforeEach(() => {
    // Login flow (from Landing -> Login -> Homepage -> Dashboard)
    cy.login('admin', 'password123');
  });

  it('Should add a new RTLH record using the multi-step modal', () => {
    // 1. Open "Perumahan" Dropdown in Sidebar
    cy.get('nav#sidebar-nav').contains('Perumahan').click({ force: true });
    
    // 2. Click "Data Rumah" sub-menu
    cy.get('#dropdown-perumahan').contains('Data Rumah').click({ force: true });
    cy.url().should('include', '/rtlh');
    cy.url().should('not.include', '/bansos-rtlh');

    // 3. Click "Tambah" button to open the modal
    cy.get('button').contains('Tambah').click();
    cy.get('#modal-rtlh').should('be.visible');

    // --- STEP 1: IDENTITAS & LOKASI ---
    cy.get('#inp_nama').type(nama);
    cy.get('#inp_nik').type(nik);
    cy.get('#inp_no_kk').type(no_kk);
    cy.get('#inp_jk').select('L');
    cy.get('#inp_jml_keluarga').clear().type('4');
    cy.get('#inp_tempat_lahir').type('Sinjai');
    cy.get('#inp_tgl_lahir').type('1990-01-01');
    
    // Select dropdowns by index 1 to be safe and dynamic
    cy.get('#inp_pendidikan_id').select(1);
    cy.get('#inp_pekerjaan_id').select(1);
    cy.get('#inp_penghasilan').select(1);
    cy.get('#inp_desa_id').select(1);

    cy.get('#inp_luas_rumah').clear().type('36');
    cy.get('#inp_luas_lahan').clear().type('72');
    cy.get('#inp_alamat').type('Jl. Cypress E2E Test No. 99');
    
    // Inject coordinates value into readonly input
    cy.get('#inp_coords').invoke('val', 'POINT(120.2536 -5.1245)').trigger('change');

    // Go to Step 2
    cy.get('#btn-rtlh-next').click();
    cy.get('#step-rtlh-2').should('not.have.class', 'hidden');

    // --- STEP 2: FASILITAS ---
    cy.get('#inp_milik_rumah').select(1);
    cy.get('#inp_milik_tanah').select(1);
    cy.get('#inp_kawasan').select(1);
    cy.get('#inp_fungsi_ruang').type('Rumah Tinggal');
    cy.get('#inp_listric').select(1);
    cy.get('#inp_air').select(1);
    cy.get('#inp_jarak_sam').type('< 10 Meter');
    cy.get('#inp_desil').type('3');
    cy.get('#inp_status_backlog').select('TIDAK BACKLOG');
    cy.get('#inp_bab').select('SENDIRI');
    cy.get('#inp_tpa').type('Septic Tank');
    cy.get('#inp_status_bantuan').select('Rtlh');

    // Go to Step 3
    cy.get('#btn-rtlh-next').click();
    cy.get('#step-rtlh-3').should('not.have.class', 'hidden');

    // --- STEP 3: TEKNIS & FOTO ---
    const techFields = [
      'st_pondasi', 'st_kolom', 'st_balok', 'st_sloof', 
      'st_rangka_atap', 'st_plafon', 'st_jendela', 'st_ventilasi',
      'mat_atap', 'st_atap', 'mat_dinding', 'st_dinding',
      'mat_lantai', 'st_lantai'
    ];
    techFields.forEach(field => {
      cy.get(`#inp_${field}`).select(1);
    });

    // Submit form
    cy.get('#btn-rtlh-save').click();

    // Verify redirect and success message
    cy.url().should('include', '/rtlh');
    cy.contains('Data RTLH berhasil ditambahkan', { timeout: 15000 }).should('be.visible');
  });

  it('Should search the record, view details, and edit it', () => {
    // Navigate to RTLH list
    cy.get('nav#sidebar-nav').contains('Perumahan').click({ force: true });
    cy.get('#dropdown-perumahan').contains('Data Rumah').click({ force: true });

    // Search for the added record
    cy.get('input[name="keyword"]').type(nama + '{enter}');

    // Go to detail page
    cy.contains(nama).parents('tr').find('a[title="Detail Master Data"]').click();
    cy.url().should('include', '/rtlh/detail/');

    // Click Perbarui Data
    cy.get('button').contains('Perbarui Data').click();
    cy.get('#modal-rtlh').should('be.visible');

    // Update Name
    cy.get('#inp_nama').clear().type(newNama);

    // Click next through steps and save
    cy.get('#btn-rtlh-next').click();
    cy.get('#btn-rtlh-next').click();
    cy.get('#btn-rtlh-save').click();

    // Verify update success
    cy.url().should('include', '/rtlh/detail/');
    cy.contains('Data RTLH berhasil diperbarui', { timeout: 15000 }).should('be.visible');
  });

  it('Should delete the updated record from list page', () => {
    // Navigate to RTLH list
    cy.get('nav#sidebar-nav').contains('Perumahan').click({ force: true });
    cy.get('#dropdown-perumahan').contains('Data Rumah').click({ force: true });

    // Search for the updated record
    cy.get('input[name="keyword"]').type(newNama + '{enter}');

    // Click Trash Icon
    cy.contains(newNama).parents('tr').find('button[title="Hapus"]').click();

    // Click OK on confirmation
    cy.get('#confirm-ok').should('be.visible').click({ force: true });

    // Verify deletion
    cy.contains('Data dipindahkan ke Recycle Bin', { timeout: 15000 }).should('be.visible');
    cy.contains(newNama).should('not.exist');
  });
});

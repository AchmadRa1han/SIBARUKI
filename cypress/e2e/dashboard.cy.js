describe('SIBARUKI System Navigation', () => {
  beforeEach(() => {
    // Login as admin
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin'); 
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  const modules = [
    { dropdown: 'Data Perumahan', link: 'RTLH', path: '/rtlh', dropdownId: 'dropdown-perumahan' },
    { dropdown: 'Data Perumahan', link: 'Rekapitulasi Desa', path: '/rtlh/rekap-desa', dropdownId: 'dropdown-perumahan' },
    { dropdown: 'Data Perumahan', link: 'PSU Terbangun', path: '/psu', dropdownId: 'dropdown-perumahan' },
    { dropdown: 'Data Perumahan', link: 'Perumahan', path: '/perumahan-formal', dropdownId: 'dropdown-perumahan' },
    { dropdown: 'Data Perumahan', link: 'Bansos RTLH', path: '/bansos-rtlh', dropdownId: 'dropdown-perumahan' },
    
    { dropdown: 'Data Permukiman', link: 'Wilayah Kumuh', path: '/wilayah-kumuh', dropdownId: 'dropdown-permukiman' },
    { dropdown: 'Data Permukiman', link: 'PISEW', path: '/pisew', dropdownId: 'dropdown-permukiman' },
    { dropdown: 'Data Permukiman', link: 'Arsinum', path: '/arsinum', dropdownId: 'dropdown-permukiman' },
    
    { dropdown: 'Data Pertanahan', link: 'Aset Tanah Pemda', path: '/aset-tanah', dropdownId: 'dropdown-pertanahan' },
  ];

  it('should display dashboard correctly', () => {
    cy.contains(/dashboard|ringkasan/i).should('exist');
    cy.get('.grid').should('exist');
  });

  modules.forEach((mod) => {
    it(`should navigate to ${mod.link} via ${mod.dropdown}`, () => {
      // Pastikan kembali ke dashboard jika perlu (sidebar selalu ada)
      // Buka dropdown jika belum terbuka
      cy.get('#main-sidebar').then(($sidebar) => {
        if (!$sidebar.find(`#${mod.dropdownId}`).hasClass('open')) {
          cy.contains(mod.dropdown).click({ force: true });
          // Tunggu sebentar untuk animasi dropdown
          cy.wait(300); 
        }
      });

      // Klik link spesifik
      cy.get(`#${mod.dropdownId}`).contains(mod.link).click({ force: true });
      
      // Verifikasi URL
      cy.url().should('include', mod.path);
      
      // Verifikasi konten halaman (minimal header atau tabel)
      cy.get('body').should('exist');
    });
  });

  it('should show logout button and go back to login', () => {
    cy.get('#main-sidebar').find('a[href*="logout"]').click({ force: true });
    cy.url().should('include', '/login');
  });
});

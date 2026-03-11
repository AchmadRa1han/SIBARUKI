describe('Authentication', () => {
  beforeEach(() => {
    // Asumsi URL lokal adalah localhost:8080
    cy.visit('http://localhost:8080/login');
  });

  it('should display login page', () => {
    cy.get('form').should('exist');
    cy.contains(/login|masuk/i).should('exist');
  });

  it('should show error on invalid credentials', () => {
    cy.get('input[name="username"]').type('invaliduser');
    cy.get('input[name="password"]').type('wrongpassword');
    cy.get('button[type="submit"]').click();
    
    // Periksa apakah pesan error muncul
    cy.get('.bg-rose-50, .bg-red-500, .alert-danger').should('exist');
  });

  it('should login successfully with valid credentials', () => {
    // Sesuaikan username/password dengan data seeder
    cy.get('input[name="username"]').type('admin'); 
    cy.get('input[name="password"]').type('password123'); // Asumsi password default
    cy.get('button[type="submit"]').click();

    // Verifikasi diarahkan ke dashboard
    cy.url().should('include', '/dashboard');
    cy.contains(/dashboard|ringkasan/i).should('exist');
  });

  it('should logout successfully', () => {
    // Login dulu
    cy.get('input[name="username"]').type('admin'); 
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    
    // Klik tombol logout di sidebar (footer area)
    // Mencari ikon logout atau link logout
    cy.get('#main-sidebar').find('a[href*="logout"]').click({ force: true });
    
    // Verifikasi kembali ke halaman login
    cy.url().should('include', '/login');
  });
});

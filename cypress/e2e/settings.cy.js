const settingsModules = [
  { path: 'roles', title: 'Roles' },
  { path: 'users', title: 'Users' },
  { path: 'ref-master', title: 'Reference Master' },
  { path: 'trash', title: 'Recycle Bin' },
  { path: 'logs', title: 'System Logs' },
];

describe('Settings & System Modules', () => {
  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin'); 
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
  });

  settingsModules.forEach((module) => {
    it(`should access ${module.title} page`, () => {
      cy.visit(`http://localhost:8080/${module.path}`);
      cy.url().should('include', `/${module.path}`);
    });
  });

  it('should access general settings page', () => {
    cy.visit('http://localhost:8080/settings');
    cy.url().should('include', '/settings');
    cy.contains(/pengaturan|sistem/i).should('exist');
  });
});

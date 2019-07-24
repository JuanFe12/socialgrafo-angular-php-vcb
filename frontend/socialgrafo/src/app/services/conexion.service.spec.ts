import { TestBed } from '@angular/core/testing';

import { ConexionService } from './conexion.service';

describe('ConexionService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ConexionService = TestBed.get(ConexionService);
    expect(service).toBeTruthy();
  });
});

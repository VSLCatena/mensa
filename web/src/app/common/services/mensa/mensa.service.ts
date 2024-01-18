import { Injectable } from '@angular/core';
import { Mensa } from '../../models/mensa.model';
import { environment } from 'src/environments/environment';
import { BaseService } from '../base.service';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MensaService extends BaseService {

  getMensae(): Observable<Mensa[]> {
    return this.get<Mensa[]>(`${environment.apiUrl}/mensa`);
  }
}

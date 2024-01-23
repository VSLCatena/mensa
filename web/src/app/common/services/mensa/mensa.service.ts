import { MensaDto } from 'src/app/common/models/dto/mensa.dto';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { BaseService } from '../base.service';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MensaService extends BaseService {

  getMensae(): Observable<MensaDto[]> {
    return this.get<MensaDto[]>(`${environment.apiUrl}/mensa`);
  }
}

import { MensaDto } from 'src/app/common/models/dto/mensa.dto';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { BaseService } from '../base.service';
import { Observable } from 'rxjs';
import { CreateMensaDto } from '../../models/dto/create-mensa.dto';
import { Mensa } from '../../models/mensa.model';

@Injectable({
	providedIn: 'root'
})
export class MensaService extends BaseService {
	getMensae(page: number = 0): Observable<MensaDto[]> {
		return this.get<MensaDto[]>(`${environment.apiUrl}/mensa?page=${page}`);
	}

	createMensa(mensa: CreateMensaDto): Observable<void> {
		return this.post<void>(`${environment.apiUrl}/mensa`, mensa);
	}

	getMensa(id: number): Observable<Mensa> {
		return this.get<Mensa>(`${environment.apiUrl}/mensa/${id}`);
	}
}

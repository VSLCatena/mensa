import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { BaseService } from '../base.service';
import { Observable } from 'rxjs';
import { MensaUserDto } from '../../models/dto/mensa-user.dto';

@Injectable({
	providedIn: 'root'
})
export class MensaUserService extends BaseService {

	registerMensaUser(mensaId: number, mensaUserDto: MensaUserDto): Observable<void> {
		return this.post<void>(`${environment.apiUrl}/mensa-user/${mensaId}`, mensaUserDto);
	}
}

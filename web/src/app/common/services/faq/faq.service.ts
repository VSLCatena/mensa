import { Injectable } from '@angular/core';
import { BaseService } from '../base.service';
import { Faq } from '../../models/faq.model';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class FaqService extends BaseService {

  getFaqs(): Observable<Faq[]> {
		return this.get<Faq[]>(`${environment.apiUrl}/faq`);
	}
}

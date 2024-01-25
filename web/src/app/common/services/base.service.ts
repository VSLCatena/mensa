import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
	providedIn: 'root'
})
export class BaseService {
	constructor(private http: HttpClient) {}

	protected get<T>(url: string): Observable<T> {
		return this.http.get<T>(url, this.generateOptions());
	}

	protected post<T>(url: string, body: unknown): Observable<T> {
		return this.http.post<T>(url, body, this.generateOptions());
	}

	protected put<T>(url: string, body: unknown): Observable<T> {
		return this.http.put<any>(url, body, this.generateOptions());
	}

	protected sendDeleteRequest(url: string): Observable<any> {
		return this.http.delete<any>(url, this.generateOptions());
	}

	private generateOptions(): {
		headers: HttpHeaders;
		withCredentials: boolean;
	} {
		const headers = new HttpHeaders({
			'Content-Type': 'application/json'
		});
		return { headers, withCredentials: true };
	}
}

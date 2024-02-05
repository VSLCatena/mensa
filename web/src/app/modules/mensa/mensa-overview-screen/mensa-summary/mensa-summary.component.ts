import { Component, Input, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { Mensa } from 'src/app/common/models/mensa.model';

@Component({
  selector: 'app-mensa-summary',
  templateUrl: './mensa-summary.component.html',
  styleUrl: './mensa-summary.component.scss'
})
export class MensaSummaryComponent implements OnInit {

  public mensa!: Mensa;
  @Input({required: true}) mensaObservable!: Observable<Mensa>;

  ngOnInit(): void {
    this.mensaObservable.subscribe(mensa => {
      this.mensa = mensa;
    });
  }
}

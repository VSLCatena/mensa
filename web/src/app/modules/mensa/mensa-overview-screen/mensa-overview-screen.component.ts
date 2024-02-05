import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Observable } from 'rxjs';
import { Mensa } from 'src/app/common/models/mensa.model';
import { MensaService } from 'src/app/common/services/mensa/mensa.service';

@Component({
  selector: 'app-mensa-overview-screen',
  templateUrl: './mensa-overview-screen.component.html',
  styleUrl: './mensa-overview-screen.component.scss'
})
export class MensaOverviewScreenComponent {

  public mensa!: Mensa;
  public mensaObservable!: Observable<Mensa>;

  constructor(private route: ActivatedRoute, private readonly mensaService: MensaService) {
    this.route.params.subscribe( params => {
      this.mensaObservable = this.mensaService.getMensa(params['id'])
      this.mensaObservable.subscribe(mensa => {
        this.mensa = mensa;
      });
    });
  }
}

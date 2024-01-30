import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Mensa } from 'src/app/common/models/mensa.model';

@Component({
  selector: 'app-mensa-overview-screen',
  templateUrl: './mensa-overview-screen.component.html',
  styleUrl: './mensa-overview-screen.component.scss'
})
export class MensaOverviewScreenComponent {

  public mensa!: Mensa;

  constructor(private route: ActivatedRoute) {
    this.route.params.subscribe( params => {
      // Retrieve mensa
    });

}
}

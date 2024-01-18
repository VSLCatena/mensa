import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeRoutingModule } from './home-routing.module';
import { HomeScreenComponent } from './home-screen/home-screen.component';
import { MensaService } from '../common/services/mensa/mensa.service';

@NgModule({
	declarations: [HomeScreenComponent],
	imports: [CommonModule, HomeRoutingModule],
	providers: [MensaService]
})
export class HomeModule {}

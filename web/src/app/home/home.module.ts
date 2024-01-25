import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeRoutingModule } from './home-routing.module';
import { HomeScreenComponent } from './home-screen/home-screen.component';
import { MensaService } from '../common/services/mensa/mensa.service';
import { AuthService } from '../common/services/login/auth.service';

@NgModule({
	declarations: [HomeScreenComponent],
	imports: [HomeRoutingModule, CommonModule],
	providers: [MensaService, AuthService]
})
export class HomeModule {}

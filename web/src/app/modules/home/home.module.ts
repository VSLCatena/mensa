import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeRoutingModule } from './home-routing.module';
import { HomeScreenComponent } from './home-screen/home-screen.component';
import { MensaService } from '../../common/services/mensa/mensa.service';
import { AuthService } from '../../common/services/auth/auth.service';
import { MenuCollapseComponent } from './menu-collapse/menu-collapse.component';
import { CollapseModule } from 'ngx-bootstrap/collapse';
import { MensaButtonComponent } from './mensa-button/mensa-button.component';

@NgModule({
	declarations: [
		HomeScreenComponent,
		MenuCollapseComponent,
		MensaButtonComponent
	],
	imports: [HomeRoutingModule, CommonModule, CollapseModule.forRoot()],
	providers: [MensaService, AuthService]
})
export class HomeModule {}

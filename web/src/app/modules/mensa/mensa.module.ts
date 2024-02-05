import { MensaRoutingModule } from './mensa-routing.module';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MensaCreationScreenComponent } from './mensa-create-screen/mensa-creation-screen.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { KeysPipe } from '../../common/pipes/keys.pipe';
import { AlertComponent } from '../../common/components/alert/alert.component';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { MensaOverviewScreenComponent } from './mensa-overview-screen/mensa-overview-screen.component';
import { MensaSummaryComponent } from './mensa-overview-screen/mensa-summary/mensa-summary.component';

@NgModule({
	declarations: [MensaCreationScreenComponent, KeysPipe, AlertComponent, MensaOverviewScreenComponent, MensaSummaryComponent],
	imports: [
		MensaRoutingModule,
		CommonModule,
		FormsModule,
		ReactiveFormsModule,
		BsDatepickerModule.forRoot()
	]
})
export class MensaModule {}

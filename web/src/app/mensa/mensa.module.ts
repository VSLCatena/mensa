import { MensaRoutingModule } from './mensa-routing.module';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MensaCreationScreenComponent } from './mensa-create-screen/mensa-creation-screen.component';

@NgModule({
	declarations: [MensaCreationScreenComponent],
	imports: [MensaRoutingModule, CommonModule]
})
export class MensaModule {}

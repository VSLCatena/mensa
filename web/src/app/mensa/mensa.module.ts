import { MensaRoutingModule } from './mensa-routing.module';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MensaCreationScreenComponent } from './mensa-create-screen/mensa-creation-screen.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { KeysPipe } from '../common/pipes/keys.pipe';

@NgModule({
	declarations: [MensaCreationScreenComponent, KeysPipe],
	imports: [MensaRoutingModule, CommonModule, FormsModule, ReactiveFormsModule]
})
export class MensaModule {}
